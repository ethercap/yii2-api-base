<?php

namespace ethercap\apiBase\components;

use ethercap\apiBase\components\responseTemplates\BlockTpl;
use ethercap\apiBase\components\responseTemplates\NoBlockTpl;
use ethercap\apiBase\components\responseTemplates\TemplateManager;
use Yii;
use yii\base\Component;
use yii\web\Response;
use ethercap\apiBase\components\json\Formatter;

/**
 * Class ResBuilder
 * @package ethercap\apiBase\components
 *
 * @property TemplateManager $tm
 * @property string $id
 */
class ResBuilder extends Component
{
    public $renderPartial;
    public $rtData;
    public $errorHandler;
    public $templates = [];
    public $tm = TemplateManager::class;
    public $autoChoose;

    protected $_errModel;
    protected $_data;
    protected $_fields = [];
    protected $_errorModels = [];
    protected $_builtInTemplates = [
        'block' => [
            'class' => BlockTpl::class,
        ],
        'noBlock' => [
            'class' => NoBlockTpl::class,
        ],
    ];

    protected $_id;

    public function getId()
    {
        if (!$this->_id) {
            $this->_id = spl_object_hash($this);
        }
        return $this->_id;
    }

    public function init()
    {
        parent::init();
        $this->tm = new TemplateManager();
        $this->initTemplates();
    }

    public function initErrorStack()
    {
        ResBuilderErrorStack::getInstance($this->id);
    }

    protected function initTemplates()
    {
        $templates = array_merge($this->_builtInTemplates, $this->templates);
        foreach ($templates as $k => $v) {
            $this->tm->setSingleton($k, $v);
        }
        if (!$this->autoChoose) {
            $this->tm->autoChoose = function () {
                if (Yii::$app->request->isPost) {
                    return 'block';
                }
                return 'noBlock';
            };
        }
    }

    public function use($name)
    {
        $this->tm->use($name);
    }

    public function run()
    {
        Yii::$app->response->formatters[Response::FORMAT_JSON] = Formatter::class;
        $this->processData();
        if ($this->errorHandler && is_callable($this->errorHandler)) {
            if ($this->id == ResBuilderErrorStack::getInstance($this->id)->creator) {
                $error = clone ResBuilderErrorStack::getInstance($this->id);
                ResBuilderErrorStack::getInstance($this->id)->clear();
                return call_user_func($this->errorHandler, $error, $this);
            }
        }
        if ($this->renderPartial) {
            return $this->rtData;
        }
        return $this->tm->loadBuilder($this)->getRes();
    }

    /**
     * @param null $value
     * @param array $default
     * @return Value|null
     */
    public function data($value = null, $default = [])
    {
        if ($value !== null) {
            return $this->_data = $value;
        } else {
            return $this->_data = new Value(['default' => $default, 'builder' => $this]);
        }
    }

    /**
     * @param null $key
     * @param null $value
     * @param array $default
     * @return Value|null
     */
    public function field($key = null, $value = null, $default = [])
    {
        if ($key === null) {
            return $this->data($value, $default);
        } elseif ($value !== null) {
            return $this->_fields[$key] = $value;
        } else {
            return $this->_fields[$key] = new Value(['default' => $default, 'builder' => $this]);
        }
    }

    public function getErrModel()
    {
        return ResBuilderErrorStack::getInstance($this->id)->getErrorModel();
    }

    protected function processData()
    {
        if ($this->rtData !== null) {
            return $this->rtData;
        }
        $this->rtData = $this->buildData();
        if (!is_array($this->rtData)) {
            return $this->rtData;
        }
        $this->buildFields();
        return $this->rtData;
    }

    protected function buildData()
    {
        if ($this->_data instanceof Value) {
            return $this->_data->evaluate();
        }
        return isset($this->_data) ? $this->_data : [];
    }

    protected function buildFields()
    {
        foreach ($this->_fields as $field => $value) {
            if ($value instanceof Value) {
                $this->rtData[$field] = $value->evaluate();
                continue;
            }
            $this->rtData[$field] = $value;
        }
    }

    public function pushError($model)
    {
        return ResBuilderErrorStack::getInstance($this->id)->pushError($model);
    }

    public function hasError()
    {
        return ResBuilderErrorStack::getInstance($this->id)->hasError();
    }

    public function __clone()
    {
        $this->_id = null;
    }
}

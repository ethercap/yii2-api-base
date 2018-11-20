<?php

/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";

?>
return [
<?php
$count = 0;
foreach ($generator->getColumnNames() as $name) {
    echo "        '" . $name . "',\n";
}
?>
];

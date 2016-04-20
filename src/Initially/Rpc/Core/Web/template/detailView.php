<!DOCTYPE html>
<html>
<head>
    <title>Service Detail Info - Initially Rpc</title>
    <style type="text/css">
        body {
            font: 14px/20px Verdana, Arial, sans-serif;
            color: #333;
            background: #f8f8f8;
        }
        body, h1, h2, pre {
            margin: 0;
            padding: 0;
        }
        h1 {
            font: bold 28px Verdana,Arial;
            background: #99c;
            padding: 12px 5px;
            border-bottom: 4px solid #669;
            box-shadow: 0 1px 4px #bbb;
            color: #222;
        }
        h2 {
            font: normal 20px/22px Georgia, Times, "Times New Roman", serif;
            padding: 5px 0 8px;
            margin: 20px 10px 0;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }
        pre {
            margin-top: 10px;
            margin-left: 30px;
            white-space: pre-line;
        }
    </style>
</head>
<body>
    <!-- Power by initially-rpc-1.0.0  -->
    <h1>Initially Rpc Server: <?php echo $reflectionClass->getName(); ?></h1>
    <?php foreach ($reflectionClass->getMethods() as $method): ?>
        <h2>
            <?php
                $info = $reflectionClass->getName() . "::" . $method->getName();
                $parameterString = "";
                foreach ($method->getParameters() as $parameter) {
                    if ($parameter->isArray()) {
                        $parameterString .= "array \${$parameter->getName()}";
                    } elseif (!is_null($parameter->getClass())) {
                        $parameterString .= "{$parameter->getClass()->getName()} \${$parameter->getName()}";
                    } else {
                        $parameterString .= "\${$parameter->getName()}";
                    }

                    if ($parameter->isDefaultValueAvailable()) {
                        $defaultValue = $parameter->getDefaultValue();
                        if (is_array($defaultValue)) {
                            $parameterString .= " = " . str_replace("\n", "", var_export($defaultValue, true));
                        } elseif (is_string($defaultValue)) {
                            $parameterString .= " = \"{$defaultValue}\"";
                        } elseif (is_null($defaultValue)) {
                            $parameterString .= " = null";
                        } elseif (is_bool($defaultValue)) {
                            $parameterString .= " = " . ($defaultValue ? "true" : "false");
                        } else {
                            $parameterString .= " = {$defaultValue}";
                        }
                    }

                    $parameterString .= ", ";
                }

                echo $info . "(" . trim($parameterString, " ,") . ")";
            ?>
        </h2>
        <pre><?php echo $method->getDocComment(); ?></pre>
    <?php endforeach; ?>
</body>
</html>
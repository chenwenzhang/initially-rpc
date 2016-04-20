<!DOCTYPE html>
<html>
<head>
    <title>Service List - Initially Rpc</title>
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
        h2 a {
            color: #000;
            text-decoration: none;
        }
        h2 a:hover {
            text-decoration: underline;
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
    <h1>Initially Rpc Server: Server List</h1>
    <?php foreach ($configs as $value): ?>
        <h2>
            <a href="<?php echo $_SERVER["REQUEST_URI"] . "?action=detail&interface={$value->getInterface()}"; ?>" target="_blank">
                <?php echo $value->getInterface(); ?>
            </a>
        </h2>
    <?php endforeach; ?>
</body>
</html>
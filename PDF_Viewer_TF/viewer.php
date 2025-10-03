<?php
// Get the form data regardless of method (GET or POST)
$formData = array_merge($_GET, $_POST);
$method = $_SERVER['REQUEST_METHOD'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Viewer</title>
    <link rel="stylesheet" href="php.css">
</head>
<body>
    <h1>Form Data Viewer</h1>
        
        <div class="method-info">
            <strong>Request Method:</strong> <?php echo htmlspecialchars($method); ?>
        </div>

        <?php if (empty($formData)): ?>
            <div class="no-data">
                <p>No data received. Please submit the form first.</p>
            </div>
        <?php else: ?>
            <div class="data-count">
                <?php echo count($formData); ?> fields of submitted data
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($formData as $key => $value): ?>
                        <tr>
                            <td class="field-name">
                                <?php echo htmlspecialchars($key); ?>
                            </td>
                            <td class="field-value">
                                <?php
                                if (is_array($value)) {

                                    if (empty($value)) {
                                        echo '<span class="empty-value">(empty array)</span>';
                                    } else {
                                        echo '<ul class="array-list">';
                                        foreach ($value as $item) {
                                            echo '<li>' . htmlspecialchars($item) . '</li>';
                                        }
                                        echo '</ul>';
                                    }
                                } else {
                                    if ($value === '' || $value === null) {
                                        echo '<span class="empty-value">(empty)</span>';
                                    } else {
                                        echo htmlspecialchars($value);
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
    <div class="back-link">
        <a href="index.html">&larr; Back to Form</a>
    </div>
</body>
</html>
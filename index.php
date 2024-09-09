<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Calculator</title>
</head>
<body>
    <form method="post" action="">
        <label for="income">Income:</label>
        <input type="text" name="income" id="income" required>
        <br>
        <label for="age">Age:</label>
        <input type="text" name="age" id="age" required>
        <br>
        <input type="submit" value="Calculate Tax">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $income = floatval($_POST["income"]);
        $age = intval($_POST["age"]);

        function calculateTax($income, $age) {
            // Define tax brackets and rates for 2025
            $brackets = [
                [237101, 370500, 0.26, 42678],
                [370501, 512800, 0.31, 77362],
                [512801, 673000, 0.36, 121475],
                [673001, 857900, 0.39, 179147],
                [857901, 1817000, 0.41, 251258],
                [1817001, INF, 0.45, 644489]
            ];
            
            // Calculate the rebate based on age
            $primaryRebate = 17235;
            $secondaryRebate = 9444;
            $tertiaryRebate = 3145;

            if ($age < 65) {
                $rebate = $primaryRebate;
            } elseif ($age < 75) {
                $rebate = $primaryRebate + $secondaryRebate;
            } else {
                $rebate = $primaryRebate + $secondaryRebate + $tertiaryRebate;
            }

            // Calculate tax for income
            $tax = 0;
            if ($income <= 237100) {
                // If income is within the first bracket
                $tax += $income * 0.18;
            } else {
                // Calculate tax for the first bracket
                $tax += 237100 * 0.18;

                // Calculate tax for higher brackets
                foreach ($brackets as $bracket) {
                    if ($income > $bracket[0]) {
                        $tax += $bracket[3] + ($income - $bracket[0]) * $bracket[2];
                    }
                }
            }

            // Apply rebate
            $tax -= $rebate;
            
            return max($tax, 0); // Ensure tax is not negative
        }

        $tax = calculateTax($income, $age);
        echo "<h2>Calculated Tax: R$tax</h2>";
    }
    ?>
</body>
</html>

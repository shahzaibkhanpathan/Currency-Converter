<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .icon-button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            margin: 0 10px;
            color: gray;
            transition: color 0.3s;
        }
        .icon-button:hover {
            color: #28a745; /* Change text color on hover */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1>Currency Converter</h1>
    <input type="number" id="amount" placeholder="Amount" class="form-control">
    
    <select id="fromCurrency" class="form-control"></select>

    <!-- Icons for swapping currencies -->
    <div class="icon-container text-center mb-3">
        <!-- <button class="icon-button" id="exchangeBtn1">
            <i class="fas fa-exchange-alt"></i>
        </button> -->
        <button class="icon-button" id="exchangeBtn2">
            <i class="fas fa-sync-alt"></i>
        </button>
        <!-- <button class="icon-button" id="exchangeBtn3">
            <i class="fas fa-retweet"></i>
        </button> -->
    </div>

    <select id="toCurrency" class="form-control"></select>
    <button id="convertBtn">Convert</button>
    <h2 id="result"></h2>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    const apiKey = 'a50ec2dce8e5e1f02ee510be';
    const fromCurrencySelect = $('#fromCurrency');
    const toCurrencySelect = $('#toCurrency');
    const amountInput = document.getElementById('amount');
    const convertBtn = document.getElementById('convertBtn');
    const resultDisplay = document.getElementById('result');

    const currencies = [
        { code: 'PKR', country: 'Pakistani Rupee' },
        { code: 'AFN', country: 'Afghan Afghani' },
        { code: 'BDT', country: 'Bangladeshi Taka' },
        { code: 'INR', country: 'Indian Rupee' },
        { code: 'LKR', country: 'Sri Lankan Rupee' },
        { code: 'NPR', country: 'Nepalese Rupee' },
        { code: 'AUD', country: 'Australian Dollar' },
        { code: 'NZD', country: 'New Zealand Dollar' },
        { code: 'ZAR', country: 'South African Rand' },
        { code: 'USD', country: 'United States Dollar' },
        { code: 'EUR', country: 'Euro' },
        { code: 'GBP', country: 'British Pound' }
    ];

    const populateCurrencyDropdown = (selectElement) => {
        selectElement.empty(); // Clear existing options
        currencies.forEach(({ code, country }) => {
            const option = new Option(`${code} - ${country}`, code);
            selectElement.append(option);
        });
    };

    populateCurrencyDropdown(fromCurrencySelect);
    populateCurrencyDropdown(toCurrencySelect);

    fromCurrencySelect.select2();
    toCurrencySelect.select2();

    const convertCurrency = async () => {
        const fromCurrency = fromCurrencySelect.val();
        const toCurrency = toCurrencySelect.val();
        const amount = amountInput.value;

        const url = `https://v6.exchangerate-api.com/v6/${apiKey}/pair/${fromCurrency}/${toCurrency}/${amount}`;
        
        try {
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.result === "error") {
                resultDisplay.textContent = `Conversion failed: ${data['error-type']}`;
            } else if (data.conversion_rate) {
                const convertedAmount = (data.conversion_rate * amount).toFixed(2);
                resultDisplay.textContent = `${amount} ${fromCurrency} (${currencies.find(c => c.code === fromCurrency).country}) = ${convertedAmount} ${toCurrency} (${currencies.find(c => c.code === toCurrency).country})`;
            } else {
                resultDisplay.textContent = 'Conversion failed.';
            }
        } catch (error) {
            resultDisplay.textContent = 'Error fetching data: ' + error.message;
        }
    };

    const swapCurrencies = () => {
        const temp = fromCurrencySelect.val();
        fromCurrencySelect.val(toCurrencySelect.val());
        toCurrencySelect.val(temp);
        fromCurrencySelect.trigger('change');
        toCurrencySelect.trigger('change');

        // Automatically convert after swapping
        convertCurrency();
    };

    convertBtn.addEventListener('click', convertCurrency);
    // document.getElementById('exchangeBtn1').addEventListener('click', swapCurrencies);
    document.getElementById('exchangeBtn2').addEventListener('click', swapCurrencies);
    // document.getElementById('exchangeBtn3').addEventListener('click', swapCurrencies);

    populateCurrencyDropdown(fromCurrencySelect);
    populateCurrencyDropdown(toCurrencySelect);
</script>
</body>
</html>

# EURCurrency
Convert all amount to and from EUR

## How to use

$currency = new EURCurrency('./files/');

echo "1&euro; = {$currency->convertTo(1, 'IDR')} IDR<br>";
echo "100$ = {$currency->convertFrom(100, 'USD')}€";

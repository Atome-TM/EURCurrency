# EURCurrency
Convert all amount to and from EUR

## How to use

<pre>
$currency = new EURCurrency('./files/');

echo "1&euro; = {$currency->convertTo(1, 'IDR')} IDR";
echo "100$ = {$currency->convertFrom(100, 'USD')}€";
</pre>

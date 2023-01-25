# Description

Completed 25/01-2023

I spent more than the intended two hours on this solution, it was perhaps closer to 5 hours.
I did not have much prior experience with php, so a portion of the time was spent learning and understanding.
I did not make any use of external libraries in this solution.

## Missing features

It is possible to convert between currencies that are not supported by the same API, though only under certain conditions.
If the chosen 'fromCurrencyApi' and 'toCurrencyApi' dont share any currencies between them ('ExchangeRateService.php', 'getExchangeAmount()'), the conversion cannot happen. The choice of 'fromCurrencyApi' and 'toCurrencyApi' is dependant on the order of the list, thus even if there are 2 API's that share currencies, it is possible that they wont be chosen.
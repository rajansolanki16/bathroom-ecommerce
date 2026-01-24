<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashfree Payment</title>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
</head>
<body>
    <script>
        const cashfree = Cashfree({
            mode: "{{ $mode === 'PRODUCTION' ? 'production' : 'sandbox' }}"
        });

        cashfree.checkout({
            paymentSessionId: "{{ $paymentSessionId }}"
        });
    </script>
</body>
</html>

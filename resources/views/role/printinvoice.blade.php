<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #{{ $invoice->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            background: white;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .invoice-header {
            text-align: left;
            margin-bottom: 20px;
        }

        .invoice-header img {
            height: 50px;
            display: block;
        }

        .invoice-header h2 {
            margin-top: 10px;
            font-weight: 700;
            color: #0056b3;
            text-align: center;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-details td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .service-title {
            font-weight: bold;
            color: #007bff;
        }

        .total-amount {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #d9534f;
            margin-top: 10px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .sign-box {
            width: 40%;
            text-align: center;
            font-weight: bold;
            position: relative;
        }

        .sign-box p {
            margin-bottom: 80px;
            /* Tăng khoảng trống để ký */
        }

        .signature-label {
            font-weight: bold;
            margin-bottom: 40px;
        }

        .print-container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .btn-print {
            display: block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            background: #007bff;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            border: none;
        }

        .btn-print:hover {
            background: #0056b3;
        }

        @media print {
            .btn-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <!-- Logo góc trái -->
            <img src="/img/logo.webp" alt="Logo">
            <h2>HÓA ĐƠN THANH TOÁN</h2>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <td><strong>Mã Hóa Đơn:</strong></td>
                    <td>#{{ $invoice->id }}</td>
                </tr>
                <tr>
                    <td><strong>Ngày Lập:</strong></td>
                    <td>{{ $invoice->invoice_date }}</td>
                </tr>
                <tr>
                    <td><strong>Tên Bệnh Nhân:</strong></td>
                    <td>{{ $invoice->patient_name }}</td>
                </tr>
                <tr>
                    <td><strong>Ngày Khám:</strong></td>
                    <td>{{ $invoice->exam_date }}</td>
                </tr>
                <tr>
                    <td><strong>Số Điện Thoại:</strong></td>
                    <td>{{ $invoice->phone }}</td>
                </tr>
                <tr>
                    <td class="service-title">Dịch Vụ:</td>
                    <td>{{ explode(';', $invoice->services_medicines)[0] ?? 'Không có' }}</td>
                </tr>
                <tr>
                    <td class="service-title">Thuốc:</td>
                    <td>{{ explode(';', $invoice->services_medicines)[1] ?? 'Không có' }}</td>
                </tr>
            </table>

            <div class="total-amount">
                Tổng Số Tiền: {{ number_format($invoice->total_amount, 0) }} VNĐ
            </div>
        </div>

        <!-- Phần ký tên -->
        <div class="signatures">
            <div class="sign-box">
                <div class="signature-label">Chữ ký bệnh nhân</div>
                <p></p> <!-- Khoảng trống để ký -->
            </div>
            <div class="sign-box">
                <div class="signature-label">Chữ ký bác sĩ thanh toán</div>
                <p></p> <!-- Khoảng trống để ký -->
            </div>
        </div>

        <!-- Nút In Hóa Đơn chính giữa -->
        <div class="print-container">
            <button class="btn-print" onclick="window.print();">
                <i class="bi bi-printer"></i> In Hóa Đơn
            </button>
        </div>
    </div>
</body>

</html>
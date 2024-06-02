Table product:-
id,
cate_id,
subCate_id
productName,
brand,
quantity,
orginal_price,
selling_price,
discount amount
shortDesc,
longDesc,
keywords,
status


Table Image:-

id,
product_id,
ImagePath,
Alt Text,
ordering,
status,
timestamp



Weight table:-
id,
product_id,
product_weight,
product_price,
product_discount,
product_coupons,
product_coupons_expiryDate,
timestamp



Stock Table:-
id,
product_id,
stock_in,
stock_out,
timestamp


userFeedback Table:-

id,
user_id,
product_id,
userRating,
comment,
timestamp


subCategory Master table
id,
category_id,
subCategory_name,


User PaymentTable
user_id
product_id
amount
transection_id
razorpay_order_id
payment_status


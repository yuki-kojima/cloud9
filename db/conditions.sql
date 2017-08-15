--  1.priceが500円以上から1,000円以下の商品データを取得する
SELECT id, name, price
FROM products
WHERE price BETWEEN 500 AND 1000;

-- 2.idが3以上で、priceが500円以上の商品データを取得する
SELECT id, name, price
from products
WHERE id >= 3 AND price >= 500;


-- 3.priceが1,500円以下で、「傘」を除く商品データを取得する
SELECT id, name, price
from products
WHERE price <= 1500 and name <> '傘';
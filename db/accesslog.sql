-- accessテーブル作成
CREATE TABLE access (
    access_datetime DATETIME COMMENT 'アクセス日時',
    user_id INTEGER COMMENT 'ユーザー名'
);

-- データの挿入
INSERT INTO access
    (access_datetime, user_id)
values
    ('2017/01/22 00:11:41',1),
    ('2017/01/22 01:33:24',3),
    ('2017/01/22 04:51:23',4),
    ('2017/01/22 12:33:21',1),
    ('2017/01/22 20:40:13',2),
    ('2017/01/23 03:29:34',1),
    ('2017/01/23 16:31:36',5),
    ('2017/01/24 08:29:57',2),
    ('2017/01/24 11:38:29',2),
    ('2017/01/24 13:59:18',1),
    ('2017/01/24 20:38:27',3),
    ('2017/01/24 23:25:11',3)
;

-- ページビューとユニークユーザ
SELECT
    DATE(access_datetime) AS 訪問日,
    COUNT(user_id) AS pv,
    COUNT(DISTINCT user_id) AS uu
FROM
    access
GROUP BY
    DATE(access_datetime);
    

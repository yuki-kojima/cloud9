<p class>カートに商品が入っていません</p>
                    <table class="cart">
                        <tr>
                            <th>商品名</th>
                            <th>単価</th>
                            <th>数量</th>
                            <th>価格</th>
                            <th>削除</th>
                        </tr>
                        <tr>
                            <td class="item_name"><div class="item_name_wrap"><img class="item_thumbnail" src="./img/milktea.png"><p>圀圀圀圀圀圀圀圀圀</p></div></td>
                            <td class="price">1,000円</td>
                            <td class="quantity">
                                <form action="./cart.php" method="post">
                                    <input type="text" name="item_quantity" placeholder="1" value="">個
                                    <input type="hidden" name="item_id" value="item_id">
                                    <button class="btn_cart_common" type="submit" name="change_quantity">変更</button>
                                </form>
                            </td>
                            <td class="price">1,000円</td>
                            <td class="delete">
                                <form action="./cart.php" method="post">
                                    <button class="btn_cart_common" type="submit" name="delete">×削除</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td class="total_price" colspan="3">小計(税込)</td>
                            <td class="right">1,000円</td>
                            <td class="total_price"></td>
                        </tr>
                    </table>
                    <form action="./shopping_complete.php" method="post">
                        <button class="btn_common btn_red btn_purchase" type="submit" name="purchase">購入を確定する</button>
                    </form>
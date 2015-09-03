<div class="product__cart" {if $cart.count==0}style="display:none;"{/if}>
    <a class="product__cart__icon" href="{siteUrl(cart/order)}">
        <span>{$cart.count}</span>
    </a>

    <div class="product__cart__box">
        <div class="product__cart__count">
            В КОРЗИНЕ: <span>{$cart.count} товара</span>
        </div>
        <div class="product__cart__summ">
            на сумму: <span>{$cart.totalPrice} грн</span>
        </div>
    </div>
</div>


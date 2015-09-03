<div class="contacts" data-js="contacts">
    <div class="contacts__top">
        <div class="inner">
            <div class="contacts__left">
                <div class="breadcrumbs">
				    <div class="inner">
				        <a href="{siteUrl()}">Главная</a> > <span>{$.page.menuname}</span>
				    </div>
				</div>         
				{if $.page.menuname}
					<h1>
	                   {$.page.menuname}
	                </h1>
                {/if}
                {if $.page.body}{$.page.body}{/if}
               
            </div>
        </div>
       
        <div class="contacts__right">
            <iframe src="https://www.google.com/maps/embed?pb=!1m12!1m8!1m3!1d2542.2018100758028!2d{$.page.map.lng}!3d{$.page.map.lat}!3m2!1i1024!2i768!4f13.1!2m1!1z0Y_QvNGB0LrQsNGPIDcy!5e0!3m2!1sru!2sua!4v1429208410967" width="650" height="506" frameborder="0" style="border:0"></iframe>
        </div>
    </div>
    <div class="contacts__bottom">
        <div class="inner">
            <div class="contacts__left">
                {if $contacts}
                	 <h2>
	                	 Контакты представительств
                    </h2>
                    <div class="contacts__list">
                    	{foreach $contacts one}
                    		 <div>
		                        <b>{$one.name}</b>
		                        {$one.phone}
		                    </div>
                    	{/foreach}
                    </div>
                {/if} 
            </div>
            
            	
            <div class="contacts__right">
            	{if $thanks}
            		<h2>{translate(thanks-contacts)}</h2>
            	{else}
                <form class="contacts__form" method="POST" action="{siteUrl(contacts/form/question/send)}">
                    <h2>Остались вопросы? Пишите нам!</h2>

                    <label for="name">Ваше Имя</label>
                    <input type="text" id="name" name="name"/>
                    <label for="email">Ваш Email</label>
                    <input type="email" id="email" name="email" />
                    <label for="msg">Сообщение</label>
                    <textarea id="msg" cols="30" rows="10" name="body"></textarea>
                    <input type="hidden" name="_token" value=""/>
                    <input type="submit" class="btn js-contacts-btn" value="Отправить"/>
                </form>
                {/if}
            </div>
            
        </div>
    </div>
</div>
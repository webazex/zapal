<?php ?>
<section class="contact" data-header="dark" id="contact">
    <div class="shell contact-grid">
        <div data-reveal="">
            <div class="kicker">Придбання та співпраця</div>
            <h2>Є питання?<span class="serif">Напишіть нам.</span></h2>
            <p class="contact-note">Усі питання щодо придбання, зразків і співпраці приймаємо через форму зворотного
                зв’язку. Оберіть продукт і залиште повідомлення.</p></div>
        <form class="form" data-recipient="zapalsauce@gmail.com" data-reveal="" method="post"
              novalidate="novalidate">
            <div class="field"><label for="product">Продукт</label><select id="product" name="product">
                    <option>Worcester</option>
                    <option>Carolina Gold</option>
                    <option>Pan Asian (Корейський Пан)</option>
                    <option>TEXAS BBQ</option>
                    <option>SRIRACHA</option>
                    <option>ELIXIR</option>
                    <option>Паста Халапеньо</option>
                    <option>Надгострі соуси</option>
                </select></div>
            <div class="field"><label for="name">Ім’я</label><input autocomplete="name" id="name" name="name"
                                                                    placeholder="Ваше ім’я" required=""/></div>
            <div class="field"><label for="phone">Телефон</label><input autocomplete="tel" id="phone" name="phone"
                                                                        placeholder="+380" required=""/></div>
            <div class="field"><label for="email">Email</label><input autocomplete="email" id="email" name="email"
                                                                      placeholder="Необов’язково" type="email"/>
            </div>
            <div class="field"><label for="message">Питання</label><textarea id="message" name="message"
                                                                             placeholder="Розкажіть, що вас цікавить"
                                                                             required=""></textarea></div>
            <div class="submit-row"><small>Відповімо щодо фасування, умов постачання та співпраці.</small>
                <button class="btn" type="submit">Задати питання ↗</button>
            </div>
        </form>
    </div>

</section>

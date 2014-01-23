@layout('templates.main')

@section('morelinks')
<meta property="og:type" content="article" /> 
<meta property="og:url" content="{{URL::full()}}" /> 
<meta property="og:title" content="О клубе Квант" /> 
<meta property="og:image" content="URL::base().'/img/cqlogotop.png'}}" />
@endsection

@section('pinned')
<?php
$pins = Pin::where('showtime_start', '<', date('c'))
         ->where('showtime_end', '>', date('c'))->get();

if(count($pins) != 0) {
    $b = IoC::resolve('bungs');

    echo '<div class="imagelayer">
            <div class="slider-wrapper theme-cq">
                <div id="slider" class="nivoSlider">';

    $linkdivs = '';
    foreach ($pins as $pinkey) {
        $linkuq = uniqid();
        $postpin = $pinkey->post()->first();
        if($postpin->img)
            echo '<img src="' . AuxImage::get_uri($postpin->img, $postpin->imgparam) 
                . '" title="#' . $linkuq . '"/>';
        else
            echo '<img src="' . $b->get_bung_img() . '" title="#' . $linkuq . '"/>';

        $linkdivs .= '<div class="nivo-caption" id="' . $linkuq . '"><a href="' 
            . URL::to_action("post@show", array("postid" => $pinkey->post_id)) 
            . '">' . $pinkey->post()->first()->title . '</a></div>';
    }

    echo '</div></div>
          <script type="text/javascript">
            $(window).load(function() {
                $("#slider").nivoSlider({
                    effect: "random",
                    directionNav: false,
                    controlNav: true
                });
            });
            </script>'
            . $linkdivs .
    '</div>';
    echo '<div class="masklayer"><img src="img/m2.png"></div>';
}
else {
    $b = IoC::resolve('bungs');

    echo '<div class="imagelayer"><img src="' . $b->get_bung_img() . '"></div>';
    echo '<div class="masklayer" style="top: -215px;"><img src="img/m2.png"></div>';
}
?>
@endsection

@section('content')

<div class="postentry" style="border:none">
    <p id="articlemain" itemprop="description">
<h3>О КЛУБЕ КВАНТ</h3>

<h4>А ты помнишь, как всё начиналось</h4>
<i>Из воспоминаний первого президента клуба «Квант» Леонида Шлесса.</i>
<p>
Стукнуло в голову, человек встал и сказал: «Да будет Квант!». Это похоже на создание Богом Вселенной. Все было не просто так. Это ответ на вопрос как возник «Квант». А теперь вопрос – почему он возник? Кому-то это было нужно, чтобы он возник. Тут надо уйти чуть глубже в историю, к моменту создания Академгородка. 
</p>
<p>
Академгородок – это искусственное образование. Он создавался в годы, которые в истории называются «годы волюнтаризма». Концентрация интеллигенции на один квадратный метр в отдалении от центра страны была небывалая, а первое время даже негде было собраться для неформального общения (и Дом Ученых и ДК Академия появились позднее). Интеллигенции надо общаться, а ведь  люди приехали, которым было  что сказать друг другу. И довольно естественно появился первый клуб-кафе «Под Интегралом» (в небольшом здании у администрации Советского района). 
</p>
<p>
Молодые ученые, которые потом стали профессорами, членкорами, академиками, общались в этом клубе-кафе «Под Интегралом». В то время Академгородок, как искусственное новообразование, как само явление очень остро конфликтовал и контрастировал с тем тяжелым духом, той тяжелой обстановкой, которая была в стране при застое. Клуб-кафе «Под Интегралом» был как свеча, как источник. «Квант» я назвал бы угольком от «Интеграла». Я считаю, что «Квант» берет свое начало от «Интеграла». 
</p>
<p>
Еще в абитуре я познакомился с людьми, которые функционировали в «Интеграле» очень и очень активно. Мне, тогда первокурснику, очень быстро вправили мозги, привили понятия свободы, широты мышления, «интеграловцы» очень хорошо объяснили мне, что такое внутреннее гражданское сопротивление. И когда «Интеграл» разгромили, чтобы искры не потухли совсем, я попытался уголек разжечь. «Квант» и клубы, которые потом возникли в университете - это тепло, общение человеческое, самовыражение. Но тем не менее, я еще раз повторю, все университетские клубы ведут начало от «Интеграла». 
</p>
После развала «Интеграла» клубы как грибы после дождя начали появляться на всех факультетах. Когда попал я на собрание лидеров клубов, которые только-только создавались, а проводил это собрание Анатолий Бурштейн, бывший президент «Интеграла», то обсуждались проблемы финансирования. Элементарно - купить чай, кофе. Куда идти? Профсоюз не дает, комсомол не дает. Решили объединиться в клубную ассоциацию - тогда это получается крупная организация! Решено было выработать принципы объединения. Все понимали, что объединяться надо, но никто не хотел себя в чем-то ущемлять. Надо было такой устав написать, который бы давал всем полную свободу, как в ООН. Тогда в пятерке существовало кафе «Полураспад», и оно было как бельмо на глазу у партийных организаций, никак не контролировалось общественностью. Там продавали кофе, вино, играл оркестр. Кафе не несло никакой идеологической нагрузки. Мы решили взять этот процесс под контроль. Честно говоря – забрать себе  холл пятерки и сделать что-то свое. Я тогда понял, что можно сделать что-то вроде «Интеграла», копию его, что ли, в студенческом варианте. В ассоциации клубов подобрались толковые, приятные ребята. Придумали название «Аскет» - ассоциация клубов, едящих торты, или клубная ассоциация. Стали знакомиться по кругу, и произошла анекдотичная ситуация. Каждый встает и представляется – вот клуб такой-то, с гумфака, вот с матфака – мы так-то называемся, доходит очередь до меня. Клуба у нас, у физиков не было. Но я почему-то с ходу произнес - Клуб физиков – «Квант». Тогда не было еще журнала «Квант», слово не было еще затаскано. Очень хотелось работать с этими ребятами, в частности с Бурштейном. Я пришел к секретарю комсомольской организации университета и сказал, что я  может и вылечу из университета, но возьмусь за это дело. 
</p>
<p>
Клуб очень много времени и сил отнимал. Сначала он искал свое лицо. Назвался груздем - полезай в кузов, как говорится. Подкрепляли делами название. Сначала было объявлено о создании клуба, потом нужно было придумать, чем заниматься. Первое, что я придумал - дать себе рекламу. Узнав, что в Ленинграде есть студенческий театр миниатюр «Интеллект 68» я решил, что неплохо этих ребят пригласить сюда, чтобы они повеселилили публику на каких-то карнавалах. В процессе подготовки к приглашению этого коллектива появились люди, которые составили костяк «Кванта». Позвонили в Ленинград, предложили приехать на майские карнавальные праздники, сообщили, что приедет 18 или 19 человек и попросили, чтобы мы приготовили им площадки для выступления. «Квант» входил в клубную ассоциацию, а она – вышла на райком комсомола. Нам дали бумажки, на которых стояли печати райкома комсомола. Надо было эти бумажки назвать билетами, продать их, собрать деньги, а на эти деньги оплатить проезд ленинградцев. И за эти деньги люди посмотрят концерт ленинградцев, который они привезут. Чтобы народ узнал о том, что будут продавать билеты, нужно было дать объявление. Появился художник, он хорошо рисовал, взяли много бумаги, в холле. Но он не знал, что писать. В результате рекламу дали такую:  
<br><i>Ленинград, Москва, Новосибирск - юмористическая программа в одном действии: «О, этот научный, научный, научный, научный мир!». Юмористическая программа без права показа по телевидению. Клуб физиков «Квант», билеты продаются у нас в комнате. Следите за рекламой, сроки будут уточнены. </i><br>
</p>
<p>
Организационные вопросы были решены. Трудности начались тогда, когда эти ребята не приехали. Они прекрасно погуляли в Москве и решили, ну его, на фиг, этот Новосибирск и улетели назад, в Ленинград. Люди нервничают, спрашивают, куда идти и где смотреть. В общем, влипли, что называется!  Я дозвонился до Ленинграда и выловил руководителя этого театра. Сказал, что Новосибирский обком будет хлопотать перед университетом, чтобы вас за эти шуточки.... И другие инстанции туда подключились и вас ждут крупные разочарования, если вы рано утром не вылетите в Новосибирск. Я ему нарисовал такую картину!  И, как говорят итальянские мафиози, сделал такое предложение, от которого он просто не мог отказаться. Через 4 часа приходит телеграмма – будем, встречайте!  Ребята славные, но из-за того, что вовремя не приехали, уже нет площадок, потому как у всех  свои планы. Я по всем администраторам бегал, а этот народ - как стена непробиваемая. А тут все веселятся, наряженные, у нас карнавал, к нам из Ленинграда приехали, сейчас концерт будет!! Как же, будет. Ленинградцы сидят в общежитии, а мы совершенно не знаем, что  делать. И вдруг вижу, идет академик Александр Данилович Александров. Я был в таком отчаянии, что готов был на любой эксцентрический поступок. Подбежал, говорю: «Александр Данилович, я хочу передать вам приглашение на концерт от ленинградских студентов, клуб «Интеллект 68». Они, когда к нам приехали, их  первый вопрос был – а где тут академик Александров? (Дело в том, что пожалуй самый золотой период в жизни Ленинградского университета, это когда он там был ректором.) Они еще к вам придут, пригласят вас персонально. Но есть тут одна проблемка!» Изложил проблемку. Он говорит: «Где тут ближайший телефон?»  Попадаем к  ректору Спартаку Беляеву, заходим, Александров говорит: «Ты почему тут со своими студентами не занимаешься, а я должен твои проблемы решать?» Звонит главному ответственному по культуре в СО АН. Все дело решилось в полчаса, дали нам Дом Ученых. В конце концов, все эти концерты состоялись. Всем понравилось, ленинградцы хорошо выступили. С Александровым я тогда очень хорошо «законтачил», он нам помогал потом неоднократно. Так что можно сказать, что этот человек качал колыбель «Кванта». Он нам пожелал - ну что ж, возбуждайтесь и излучайте!  Потом отвоевали мы холл пятерки, клуб «Полураспад» ушел. 
</p>
<p>
А Капустники родились таким образом. Были у нас закрытые клубные дела. Пописывали какие-то тексты, реагировали на те или иные события. В университете был у нас физтеховец Топчиян, который и рассказывал о капустниках, проходящих в Физтехе. Тогда состав «Кванта» структурно несколько изменился. Появились исполнители, авторы текстов. 
</p>
<p>
И вот уже не помню точно год, проходили какие-то были торжества в Риге, в Рижском университете. И нас туда пригласили, ну а мы с радостью туда приехали. А с рижанами репетировал выпускник МХАТа, профессиональный артист. Перед нашим выступлением он посмотрел и говорит: о, ребята, у вас прекрасные тексты, литература, но вы совершенно гробите их своим выступлением. И преподнес нам тогда азы режиссуры. Все это у него в комнате происходило (жил он в ТЮЗе, где и выступать должны). Вот стоит человек, руки по швам, а надо вот так поставить руки как на подоконник, высунуться, выглянуть из-за угла. Вот изгиб спины, вот наклон, этот такую позу принял, этот такую. И уже когда мы выступали в Риге после его режиссуры, мы сами убедились, что значит чувствовать сцену. Бесценный тогда был нам дан урок. В целом для «Кванта». Может поэтому впоследствии  каждый следующий капустник в сценическом плане был более профессиональном.  А схема подготовки была одна и та же - в течение года накапливались какие-то материалы, а к капустнику все это дописывалось, подписывалось, формировалось. Капустник - это не какая-то одна сюжетная линия, а капуста. Сначала винегрет, короткие заставки, а в середине - научная фантастика. Это тогда так было.
</p>
<p>
Все вырученные за капустник деньги мы отдавали в райком, поскольку у нас не было расчетного счета. Были какие-то минимальные наличные деньги, сейчас это называется представительские расходы. В старом «Кванте» была такая структура: президент и вице-президент - самые низкие должности. Человек, стоявший в дверях и следивший за порядком - вышибала, грубо говоря, это министр внутренних дел. Человек, сидевший на кассе и выдававший деньги - министр финансов. Был устав клубной ассоциации, который был основой для  всех клубов. 
</p>
<p>
Когда Борис Бондарев пришел в «Квант» (третий президент клуба), он внес свое организационное начало, дисциплинирующее. Клуб как-то структурировался к тому времени, стал более функционально определенным по каким-то направлениям. Борис на втором и на третьем капустниках сам многое сочинял, сам исполнил. Но он для «Кванта» не столько автор текстов, сколько определенное начало. Он богат интеллектуально и хороший друг, преданный человек. И что важно заметить – в клубе не приживались люди, которые хотя бы чуть-чуть фальшивили. До сих пор все старые квантовцы как родственники, как братья, хотя и живут в разных городах. 
</p>
<p>
Почему-то в то время было так, что каждый следующий президент был выше ростом предыдущего. Тимур Мустафин - славный парень был, но он трагически погиб в байдарочном походе на сибирских реках. Это был второй президент клуба. А как вообще происходила смена президентов? Ясно, что после университета на человека нахлестывали дела научные, а клуб все-таки университетский. Поэтому президентом должен был быть студент – он и посвободней и университетский. Переход власти происходил безболезненно. Не так, как  в Политбюро, только посмертно. У нас уже тогда было как в Америке, президент отработал 3-4 года и другой приходит. Власть президента - понятие было очень нежесткое. Ведь власть нужна для того, чтобы кого-то подчинять и подавлять. Если пришли люди в клуб добровольно, то управлять коллективом единомышленников - одно удовольствие. Никого не надо палкой заставлять что-то делать, и каждому находится работа по душе, по наклонностям. Заниматься любимым делом, которое тебе подходит, с одной стороны, а с другой стороны - очень приятное общество вокруг. Взаимоконтакт неоценим и незаменим. И это лежит в основе любого клуба. Собираются люди близкие по духу. А такое духовное общение необходимо человеку. Это самое главное, что в клубе есть. Надеюсь, есть до сих пор.
</p>
<p>
Клуб «Квант» внес дух Гаудеамуса в университет. Согрел «Квант» уже больше сотни людей. К КВНу приложились вместе с математиками, геологами, экономистами. Я не знаю всех квантовцев. Квантовцы каждого периода хорошо знают предыдущий и последующий. Все вместе они не знакомы, поскольку это как ты стоишь на берегу, река течет и есть память, и ты видишь, что перед и что после, а всю реку ты не видишь. Но ты чувствуешь мощь ее течения. 

<h5>Успехов тебе и долголетия, клуб «КВАНТ»!</h5>
 </p>
 <br><br>

   </p>
</div>

@endsection

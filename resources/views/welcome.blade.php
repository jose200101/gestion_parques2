@extends('adminlte::page')

@section('title', 'Pulmon Verde')

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.querySelector('a[href="{{ url('logout') }}"]');

        if (logoutLink) {
            logoutLink.addEventListener('click', function(event) {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            });
        }
    });
</script>
@stop
@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae iste aperiam voluptatum quibusdam itaque non autem mollitia facilis voluptas, sed nam quidem aut omnis! Obcaecati quidem reprehenderit voluptates incidunt voluptatem?
    Molestiae maxime sit rem. Sequi, aperiam perferendis? Totam laborum obcaecati facilis soluta ut, nam saepe enim ipsam eaque illum amet aspernatur? Consectetur aperiam odit, sint eius a tempore assumenda perferendis.
    Impedit, eligendi dolore inventore iste qui iure vel reprehenderit voluptates sequi error excepturi minus nostrum eos magni? Error, deleniti temporibus unde culpa, beatae, sequi ratione aliquid eos est laborum suscipit.
    Magnam animi sint officia dicta dolorum! Eveniet aut perspiciatis ducimus nemo ea itaque fugit, earum tempora iusto nisi praesentium? Atque sunt cupiditate sed mollitia aperiam. Ipsum quidem porro rerum ad.
    Quod officiis adipisci, rem, aut porro consequuntur saepe iure eum at veniam delectus expedita repellat assumenda corporis architecto voluptates, ab cupiditate asperiores sed placeat pariatur. Illo reprehenderit temporibus fuga obcaecati.
    Aspernatur magnam animi neque, tenetur in corporis perferendis quis beatae totam numquam. Tempore illum rem quae alias, placeat itaque reprehenderit velit eos, laboriosam quos, et inventore. Perspiciatis hic at sed?
    Doloribus reiciendis, explicabo debitis optio dicta sequi natus deleniti vel minus nisi quod distinctio voluptatibus. Atque facilis magni facere quos, autem corporis nihil ullam tenetur tempore! Asperiores expedita quidem placeat?
    Laborum amet obcaecati autem voluptatem pariatur corrupti nihil praesentium laboriosam dicta placeat nisi omnis, reiciendis quam repellat eius explicabo enim! Hic, soluta similique tempore nesciunt magnam illo quod debitis unde.
    Perferendis dolorum commodi, ad aspernatur ratione nemo quos voluptates illum iusto aliquid minus inventore veniam voluptatibus quis tempora eos fugiat! Excepturi sunt ipsam nesciunt incidunt quo nihil corporis dolor ducimus?
    Quibusdam recusandae ex harum itaque odio ducimus, commodi reiciendis deserunt tenetur maxime dolor dignissimos dicta eaque perspiciatis accusantium dolorem ipsam iste nisi minima temporibus sed deleniti illum molestiae. Ipsam, quasi?
    Possimus, laudantium. Praesentium quo similique quas placeat commodi quod omnis maiores id quidem nobis temporibus voluptatibus dignissimos, nisi nam exercitationem cumque distinctio excepturi aut ipsam perspiciatis minus impedit suscipit beatae!
    Quam, itaque iste eum cum impedit molestiae harum reiciendis aliquid similique consectetur vel aperiam eos eius, voluptatum veritatis laborum eligendi animi dicta eveniet perferendis repellendus, at doloribus ad! Alias, obcaecati.
    Nam deserunt voluptatibus suscipit non quas voluptas odio dignissimos animi tenetur numquam eos, officia velit debitis aliquam ut laborum asperiores nulla inventore! Fugiat rem id velit earum quae quis accusantium!
    Veniam vero beatae, fuga possimus assumenda facere! Iste alias aliquid, est, voluptatem sit necessitatibus quasi rerum quos nam, voluptatum eveniet quia placeat amet qui. Deserunt cumque perspiciatis doloribus dolorem debitis.
    Debitis cum suscipit magni! Maiores sed quia officia, laudantium dolor, nam minima, facilis eligendi rerum eos magnam. Itaque nulla qui et aliquid vitae deserunt quidem eum autem voluptatem aperiam. Ad.
    Neque, ipsum veniam officia quidem, velit harum accusantium animi, perferendis at cumque aliquam porro labore. Consectetur, soluta magni. Dolorum temporibus incidunt, repudiandae autem minima voluptatibus at ad perferendis enim quo.
    Velit, ipsam odit amet ea debitis deleniti ducimus quod aut non odio adipisci quis ad molestias, optio saepe aspernatur ut neque eum fuga quisquam natus quam? Alias, sequi. Soluta, quia!
    Ducimus fugit illum doloremque iste perspiciatis, ipsam eligendi consequatur facilis enim nostrum. Earum labore officiis ad vitae, tenetur pariatur odio illum ea placeat sit. Ratione dolorum doloremque earum magnam deserunt.
    Autem ipsa nulla magnam, maiores cupiditate quis id corrupti repellendus dignissimos magni fugiat aliquam commodi, officia iure voluptatum necessitatibus dolor laudantium est non odit dolore recusandae voluptates aut saepe? Temporibus?
    Temporibus sint labore sed officiis quod corporis quaerat, cumque itaque quo nisi asperiores repellat. Repudiandae eaque repellendus neque accusantium voluptatum illo modi laboriosam vitae veniam amet corporis, in dolore. Quisquam!
    Quo aliquam a maxime libero rerum impedit. Quis, vero! Necessitatibus nemo, tempore est quidem odio suscipit possimus reiciendis aliquam dolores harum. Aut modi, in placeat id voluptatem inventore dolorum quasi.
    Voluptates laborum mollitia amet dicta dolores veritatis inventore. Reprehenderit deserunt id labore possimus sed facere architecto quo beatae maxime ea? Totam itaque dolorum nisi animi ducimus quas harum eveniet nesciunt?
    Laudantium ea id sapiente maiores? Autem velit et temporibus dolores reprehenderit! Sapiente, dignissimos quibusdam? Officia ipsa modi distinctio optio inventore culpa, unde repellendus, quam ab expedita, doloribus doloremque cupiditate. Amet.
    Enim architecto blanditiis perspiciatis perferendis nesciunt ratione velit quam eum autem. Veritatis vitae ipsam nisi dolores ullam. Corrupti iste quod sit modi inventore nobis fuga dolorum, nihil excepturi. Minima, eum?
    Alias, sunt. Assumenda ut minima non nihil amet impedit, vitae porro facilis, officia optio voluptas, soluta unde fugit temporibus? Exercitationem placeat sit, eius laudantium aliquam asperiores quas minima dolores beatae.
    Sapiente fugit obcaecati, minus, quaerat voluptate placeat aliquid commodi reprehenderit dicta illum tempore dolor. Et modi harum incidunt, nostrum expedita alias porro ab suscipit pariatur ullam laborum laboriosam, excepturi cumque!
    Corporis, necessitatibus? Recusandae vitae quaerat reiciendis magnam illum veniam adipisci amet quos veritatis, ipsum porro delectus aut omnis! Exercitationem repellat debitis eius error architecto modi aliquid. In fugit porro quia.
    Laboriosam officiis laudantium cupiditate nulla sunt magni architecto? Architecto eveniet, laboriosam expedita incidunt tenetur aspernatur laborum illo quaerat temporibus odit obcaecati perferendis autem animi reiciendis nihil provident. Eum, harum magnam.
    Corrupti voluptatibus unde harum, deleniti id consequuntur dicta veniam eius rerum consequatur corporis ex magni necessitatibus rem esse, excepturi enim debitis culpa animi? Exercitationem vitae, quidem nihil ut ipsa doloribus?
    Nostrum officiis natus corrupti deleniti nam quaerat inventore dignissimos iste molestias ipsam illum ullam in, reiciendis maxime odit deserunt ad rerum voluptatem atque a fuga quisquam adipisci! Aliquam, consequuntur neque.
    Neque esse, libero obcaecati, tenetur odio, sit quo facere consequatur omnis quas labore nam vitae itaque laudantium doloribus consequuntur ipsa alias praesentium. Autem impedit iste omnis totam qui id optio.
    Facere iure ab porro. Aperiam fugit ullam, harum sapiente repudiandae libero commodi aliquam itaque modi eaque, tenetur exercitationem pariatur esse, quasi blanditiis? Vero sint officia dolores deleniti quos dignissimos possimus?
    Fugiat quia quas corrupti odio expedita tempore accusantium ad suscipit! Ipsa quis veniam ab praesentium omnis neque dolor nihil, voluptate quaerat inventore doloribus ullam consequuntur laudantium dolorem quasi impedit dolores!
    Praesentium voluptas quo, rerum labore harum animi aliquam dolorum impedit accusamus, aspernatur in molestiae a omnis, repellat dolorem at architecto sed laboriosam magni repudiandae. Explicabo molestias consequuntur blanditiis sequi odit.
    Optio impedit labore fugiat a? Veniam possimus voluptatem in. Aperiam quae hic illum exercitationem recusandae? Id quibusdam dolorum tempore natus, laudantium dolorem laborum excepturi repudiandae eligendi vero voluptatibus. Libero, amet.
    Culpa voluptatem hic consequuntur porro nulla? Laborum quasi, consequuntur impedit doloremque necessitatibus esse est magnam. Voluptatem corrupti at praesentium laboriosam, minus assumenda quo modi et, asperiores dolores ipsum molestias nesciunt!
    Magnam ea debitis expedita! Odit doloribus placeat maxime mollitia, commodi itaque numquam quo, velit facilis eius dicta distinctio temporibus consequatur sint voluptate cupiditate excepturi debitis, labore minus exercitationem illo enim?
    Veniam cupiditate libero iure architecto deserunt voluptate obcaecati, doloribus beatae similique totam aliquid dignissimos magni rerum! Porro, eaque? Praesentium vitae excepturi error aut eum nobis fugiat doloribus aliquam sit quod!
    Officia odio voluptate ex. Dolore nostrum in consequatur velit. Nesciunt eligendi praesentium alias similique labore dolor explicabo, numquam facilis veritatis error tempora architecto ad inventore tenetur animi libero aut minus.
    At rem, omnis, labore a, recusandae nihil quis aperiam ducimus praesentium beatae ipsum quisquam. Ipsum excepturi modi officiis natus suscipit perferendis deleniti, culpa nemo quidem ducimus unde praesentium vitae atque.
    Aut ad quam a distinctio vitae consectetur dolorum? Aut perferendis nam in repellat reiciendis, neque repellendus fuga rem nesciunt enim, laborum facilis commodi amet maiores aliquid obcaecati, libero doloremque pariatur?
    Sequi dolorum dolore id perferendis architecto quam ipsa illo, omnis maiores maxime exercitationem aliquid eum quas? Quas voluptatibus provident pariatur quae dignissimos, maiores, minima vero nemo eaque culpa eos cum?
    Nemo dolorem vel doloribus enim nihil dolor reiciendis, fugiat veniam beatae neque sint est accusantium deleniti! Mollitia tempore est reiciendis eum itaque cumque nisi unde, modi beatae natus quis quo.
    Quae animi esse praesentium asperiores aliquid illo adipisci modi excepturi quasi distinctio odit, obcaecati, molestias optio in, nam quisquam vero iure non fuga. Minima commodi rem voluptate nam aliquam dolores.
    Temporibus quas delectus repellendus nisi, fugiat nam harum illum beatae corporis earum saepe eligendi voluptas asperiores, ex qui aliquam sequi, omnis tempora quod unde eveniet rerum enim? Magnam, provident molestiae?
    Amet quisquam, eaque sed tenetur inventore voluptatibus ex expedita fuga repellat dolores deserunt exercitationem, qui aut corrupti reiciendis consequuntur provident officia aperiam error aliquam. Sit temporibus dolorem itaque aliquam maiores?
    Fuga nostrum ad voluptates? Perspiciatis id sunt tempora, maxime autem sapiente libero, aliquam quia necessitatibus nobis itaque cum quo aliquid, suscipit voluptas officiis. Dolores doloribus laboriosam, sed earum voluptatem ipsa.
    Nam ipsum praesentium dignissimos doloribus fugit, id illum quasi dolores placeat. Magnam, iste laudantium libero blanditiis provident veritatis inventore recusandae veniam reprehenderit? Enim a voluptates eum libero ut eaque corporis.
    Quia eveniet nemo a ut ex quasi recusandae modi nam reprehenderit enim, consectetur qui dolorem vel, rerum placeat ipsa earum. Vel numquam mollitia doloremque voluptates maiores iste placeat facere itaque.
    Illo eos aspernatur provident commodi voluptas vitae impedit. Velit possimus atque necessitatibus voluptate, ad error, dolorum ratione iure molestiae in perferendis quis dignissimos temporibus beatae voluptatem quisquam a natus! Ex!
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop

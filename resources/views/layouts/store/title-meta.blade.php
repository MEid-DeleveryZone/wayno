<meta charset="utf-8" />
<title>{{$title ?? ' '}} | <?= $client_head ? ucfirst($client_head->company_name) : 'Royo' ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
@if(isset($category))
    @if($category->translation->first())
        <meta content="{{$category->translation->first()->meta_description}}" name="description" />
        <meta name="keywords" content="{{$category->translation->first()->meta_keywords}}">
    @endif
@else
    <meta name="keywords" content="Royoorders">
@endif
<meta name="author" content="Royoorders">
<link rel="shortcut icon" href="<?= $favicon ?>">
<style>
    :root {--theme-deafult: green; }
</style>
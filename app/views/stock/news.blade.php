@extends('layout.index')

@section('content')
    <div class="row">
        <?php foreach($rss as $r) {?>
        <div class="col-sm-4 news">
            <h2><?php echo htmlSpecialChars($r->title)?></h2>
            <p><i><?php echo htmlSpecialChars($r->description)?></i></p>
            <?php foreach ($r->item as $item): ?>
            <div class="row">
                <h4><a target="_blank" href="<?php echo htmlSpecialChars($item->link)?>"><?php echo htmlSpecialChars($item->title)?></a>
                    <small><?php echo date("j.n.Y H:i", (int) $item->timestamp)?></small></h4>
                <?php if (isset($item->{'content:encoded'})):?>
                <div><?php echo $item->{'content:encoded'} ?></div>
                <?php else:?>
                <p><?php echo $item->description?></p>
                <?php endif?>
            </div>
            <?php endforeach?>
        </div>
        <?php }   ?>
    </div>
@stop
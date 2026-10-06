{extends file='layout.tpl'}

{block name="content"}
    <article class="post-page">
        <h1 class="page-title">{$post.title|escape}</h1>

        {if $post.image|default:'' != ''}
            <img class="post-page__image" src="{$post.image|escape}" alt="{$post.title|escape}">
        {/if}

        <p class="post-page__description">{$post.description|escape}</p>

        <div class="post-page__meta">
            <span>Просмотров: {$post.views|escape}</span>
            <span>{$post.published_at|escape}</span>
        </div>

        {if $post.categories|default:[]|@count > 0}
            <ul class="tag-list">
                {foreach $post.categories as $category}
                    <li>
                        <a href="/category/{$category.id|escape}">{$category.name|escape}</a>
                    </li>
                {/foreach}
            </ul>
        {/if}

        <div class="post-page__body">
            {$post.body|escape}
        </div>
    </article>

    {if $related|default:[]|@count > 0}
        <section class="related">
            <h2 class="related__title">Похожие статьи</h2>
            <ul class="post-list">
                {foreach $related as $item}
                    <li class="post-list__item">
                        <a href="/post/{$item.id|escape}">{$item.title|escape}</a>
                    </li>
                {/foreach}
            </ul>
        </section>
    {/if}
{/block}

{extends file='layout.tpl'}

{block name="content"}
    <h1 class="page-title">Главная</h1>

    {if $categories|default:[]|@count > 0}
        {foreach $categories as $category}
            <section class="category-block">
                <h2 class="category-block__title">{$category.name|escape}</h2>
                <p class="category-block__description">{$category.description|escape}</p>

                <ul class="post-list">
                    {foreach $category.posts|default:[] as $post}
                        <li class="post-list__item">
                            <a href="/post/{$post.id|escape}">{$post.title|escape}</a>
                            <span class="post-list__meta">{$post.published_at|escape}</span>
                        </li>
                    {/foreach}
                </ul>

                <a class="btn" href="/category/{$category.id|escape}">Все статьи</a>
            </section>
        {/foreach}
    {else}
        <p class="empty">Пока нет категорий со статьями.</p>
    {/if}
{/block}

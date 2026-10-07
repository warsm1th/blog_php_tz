{extends file='layout.tpl'}

{block name="content"}
    <article class="category-page">
        <h1 class="page-title">{$category.name|escape}</h1>
        <p class="category-page__description">{$category.description|escape}</p>

        <div class="toolbar">
            <div class="sort">
                <span class="sort__label">Сортировка:</span>
                <a class="sort__link{if ($sort|default:'date') == 'date'} is-active{/if}"
                   href="/category/{$category.id|escape}?sort=date&amp;page=1">по дате</a>
                <a class="sort__link{if ($sort|default:'date') == 'views'} is-active{/if}"
                   href="/category/{$category.id|escape}?sort=views&amp;page=1">по просмотрам</a>
            </div>
        </div>

        {if $posts|default:[]|@count > 0}
            <ul class="post-list">
                {foreach $posts as $post}
                    <li class="post-list__item">
                        <a href="/post/{$post.id|escape}">{$post.title|escape}</a>
                        <span class="post-list__meta">
                            {$post.published_at|escape}, просмотров: {$post.views|escape}
                        </span>
                    </li>
                {/foreach}
            </ul>

            {if ($totalPages|default:1) > 1}
                <nav class="pagination" aria-label="Страницы">
                    {for $p=1 to $totalPages}
                        <a class="pagination__link{if $p == ($page|default:1)} is-active{/if}"
                           href="/category/{$category.id|escape}?sort={$sort|default:'date'|escape}&amp;page={$p}">
                            {$p}
                        </a>
                    {/for}
                </nav>
            {/if}
        {else}
            <p class="empty">В этой категории пока нет статей.</p>
        {/if}
    </article>
{/block}

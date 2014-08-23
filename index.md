---
layout: page
title: Doumao's Blog!
tagline: Doumao's little space
---
{% include JB/setup %}

## Preface

You cannot improve your past, but you can improve your future. Once time is wasted, life is wasted.

## My Articles

<ul class="posts">
  {% for post in site.posts %}
    <li><span>{{ post.date | date_to_string }}</span> &raquo; <a href="{{ BASE_PATH }}{{ post.url }}">{{ post.title }}</a></li>
  {% endfor %}
</ul>

## To-Do

This theme is still unfinished. If you'd like to be added as a contributor, [please fork](http://github.com/plusjade/jekyll-bootstrap)!
We need to clean up the themes, make theme usage guides with theme-specific markup examples.



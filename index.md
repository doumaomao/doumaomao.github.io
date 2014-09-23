---
layout: page
title: Doumao's Blog!
tagline: Doumao's little space
---
{% include JB/setup %}

### Preface

You cannot improve your past, but you can improve your future. Once time is wasted, life is wasted.

### My Articles

<ul class="posts">
  {% for post in site.posts %}
    <li><span>{{ post.date | date_to_string }}</span> &raquo; <a href="{{ BASE_PATH }}{{ post.url }}">{{ post.title }}</a></li>
  {% endfor %}
</ul>

### Happy Life

 - Where there is life, there is hope. 
 - The early bird catches the worm.
 - Learn and live.

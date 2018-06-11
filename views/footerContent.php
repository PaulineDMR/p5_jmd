/*
TWIG
 */{% extends 'footerTemplate.twig' %}


{% block footerContent %}

{% for post in posts %}
	<!--	<li>
			<a href="">
				<h5> {{post.title}}  </h5>
			</a>

			<p>{{post.content | truncate(150, true, '...')}}</p>
			<span class="timestamp">4 hours ago</span> --> <!-- A changer -->
	<!--		<a href="#">Lire la suite...</a>
		</li> -->
	    
	{% endfor %}

{% endblock %}
 */

<?php 
    
    foreach ($post as $value) {
    ?>

    	<li>
			<a href="#"> <!-- A CHANGER -->
				<h5><?php htmlspecialchars($value->getTitle()) ?></h5>
			</a>
			<p><?php wordwrap( htmlspecialchars($value->getContent()), 150) ?></p>
			<span class="timestamp">4 hours ago</span> <!-- A CHANGER -->
			<a href="#">Lire la suite...</a>
		</li>

	<?php 
	}
	?>
    

	
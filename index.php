		<h1>Розклад занять!</h1>
		<ul id="paintings"
		data-role="list"
		data-sort-class="painting-price"
		data-sort-dir="desc"
		data-cls-list="unstyled-list row flex-justify-center mt-4"
		data-cls-list-item="cell-sm-full cell-md-one-third cell-lg-4">
<?php 
	foreach($data['days'] as $key => $day){
		print('<li>');
		print('<figcaption class="painting-name text-bold"><h4>'.$day['name'].'</h4></figcaption>
			<table width="100%" class="table striped cell-hover">
			<tr><th weight=5%>№</th><th>Час</th><th>Урок</th><th>Вчитель</th><th weight=5%></th></tr>');
		foreach($day['lessons'] as $num => $lesson){
			print('<tr>
				<td>'.$num.'</td>
				<td><span class="timeLesson button link text1">'.$lesson['time'].'</span></td>
				<td><span class="lesson button link text2">'.$lesson['lesson'].'</span></td>
				<td><span class="teacher button link text3">'.$lesson['teacher'].'</span></td>
				<td><button class="star button link" data-day="'.$key.'" data-num="'.$num.'" data-teacher="'.$lesson['teacherId'].'" data-lesson="'.$lesson['lessonId'].'"><span class="mif-star-'.$lesson['active'].'"></button></td>
			</tr>');
		}
		print('</table>');
		print('</li>');
	}
?>
		</ul>
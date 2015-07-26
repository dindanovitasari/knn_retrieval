<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modified TF IDF - KNN</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">
	<link rel="icon" href="images/labs.ico">
  </head>
  <body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Modified TF IDF KNN</a>
        </div>
        <!--<div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#"></a></li>
            <li><a href="#"></a></li>
          </ul>
        </div>--><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="col-md-12">
	<center>
	<h3>Kategorisasi Teks Bahasa Indonesia Menggunakan Modified TF IDF-KNN</h3>
	<form action="KNN.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="status" value="">
		<input id="query" name="query" type="file">
		<input type="submit">
	</form>
	</center>
	</div>
	<?php
	if (isset($_POST['status'])){
	$path_parts = pathinfo($_FILES['query']['name']);
	$query=$path_parts['basename'];
	
	include 'autoloader.php';
	// create stopword remover
	$stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
	$stopWordRemover  = $stopWordRemoverFactory->createStopWordRemover();
	// create stemmer
	$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
	$stemmer  = $stemmerFactory->createStemmer();

	// ambil kata pada Document 1
	$sentence = file_get_contents('./dok1.txt');
	$output   = $stopWordRemover->remove($sentence);
	$output   = $stemmer->stem($output);
	$tfraw1 = array_count_values(str_word_count($output, 1));

	// ambil kata pada Document 2
	$sentence_2 = file_get_contents('./dok2.txt');
	$output_2   = $stopWordRemover->remove($sentence_2);
	$output_2   = $stemmer->stem($output_2);
	$tfraw2 = array_count_values(str_word_count($output_2, 1));
	
	// ambil kata pada Document 3
	$sentence_3 = file_get_contents('./dok3.txt');
	$output_3   = $stopWordRemover->remove($sentence_3);
	$output_3   = $stemmer->stem($output_3);
	$tfraw3 = array_count_values(str_word_count($output_3, 1));
	
	// ambil kata pada Document 4
	$sentence_4 = file_get_contents('./dok4.txt');
	$output_4   = $stopWordRemover->remove($sentence_4);
	$output_4   = $stemmer->stem($output_4);
	$tfraw4 = array_count_values(str_word_count($output_4, 1));
	
	// ambil kata pada Document 5
	$sentence_5 = file_get_contents('./dok5.txt');
	$output_5   = $stopWordRemover->remove($sentence_5);
	$output_5   = $stemmer->stem($output_5);
	$tfraw5 = array_count_values(str_word_count($output_5, 1));
	
	// ambil kata pada Document 6
	$sentence_6 = file_get_contents('./dok6.txt');
	$output_6   = $stopWordRemover->remove($sentence_6);
	$output_6   = $stemmer->stem($output_6);
	$tfraw6 = array_count_values(str_word_count($output_6, 1));

	// ambil kata pada Document query
	$sentence_q = file_get_contents('./'.$query);
	$output_q   = $stopWordRemover->remove($sentence_q);
	$output_q   = $stemmer->stem($output_q);
	$tfrawq = array_count_values(str_word_count($output_q, 1));

	// satukan semua kata ke bag of words
	// $outputs = $output. ' ' . $output_2 . ' ' . $output_3  . ' ' . $output_4 . ' ' . $output_5 . ' ' . $output_6 . ' ' . $output_q;
	$outputs = $output. ' ' . $output_2 . ' ' . $output_3  . ' ' . $output_4 . ' ' . $output_5 . ' ' . $output_6;
	$bag_of_words = array_count_values(str_word_count($outputs, 1));

	//ambil kata dari bag of words 
	$words=array();
	foreach ($bag_of_words as $word=>$count){
		$words[]=$word;
	}

	// ambil kata dari dokumen 1
	$words_of_dok1=array();
	foreach ($tfraw1 as $word=>$count){
		$words_of_dok1[]=$word;
	}

	// ambil kata dari dokumen 2
	$words_of_dok2=array();
	foreach ($tfraw2 as $word=>$count){
		$words_of_dok2[]=$word;
	}
	
		// ambil kata dari dokumen 3
	$words_of_dok3=array();
	foreach ($tfraw3 as $word=>$count){
		$words_of_dok3[]=$word;
	}
	
		// ambil kata dari dokumen 4
	$words_of_dok4=array();
	foreach ($tfraw4 as $word=>$count){
		$words_of_dok4[]=$word;
	}
	
		// ambil kata dari dokumen 5
	$words_of_dok5=array();
	foreach ($tfraw5 as $word=>$count){
		$words_of_dok5[]=$word;
	}
	
		// ambil kata dari dokumen 6
	$words_of_dok6=array();
	foreach ($tfraw6 as $word=>$count){
		$words_of_dok6[]=$word;
	}

	// ambil kata dari dokumen query
	$words_of_query=array();
	foreach ($tfrawq as $word=>$count){
		$words_of_query[]=$word;
	}

	//Inverted Index
	define("DOC_ID", 0);
	define("TERM_POSITION", 1);
	$D[0]=$output;
	$D[1]=$output_2;
	$D[2]=$output_3;
	$D[3]=$output_4;
	$D[4]=$output_5;
	$D[5]=$output_6;
	for($doc_num=0; $doc_num < count($D); $doc_num++) {      
		// zero array containing document terms
		$doc_terms = array();      
		// simplified word tokenization process
		$doc_terms = explode(" ", $D[$doc_num]);      
		// here is where the indexing of terms to document locations happens
		$num_terms = count($doc_terms);
		for($term_position=0; $term_position < $num_terms; $term_position++) {
			$term = $doc_terms[$term_position];
			$corpus_terms[$term][]=array($doc_num, $term_position);
			}      
	}
	// sort by key for alphabetically ordered output
	ksort($corpus_terms);

	//============================================================ TF DF ================================================
	$jumlah_tf1=$bag_of_words;
	$jumlah_df1=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		if (array_key_exists($words[$i],$tfraw1)){
			$jumlah_tf1[$words[$i]]=1+(log10($tfraw1[$words[$i]]));
			$jumlah_df1[$words[$i]]=1;
			}	
		else {
			$jumlah_tf1[$words[$i]]=0;
			$jumlah_df1[$words[$i]]=0;
			}	
		}

	$jumlah_tf2=$bag_of_words;
	$jumlah_df2=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		if (array_key_exists($words[$i],$tfraw2)){
			$jumlah_tf2[$words[$i]]=1+log10($tfraw2[$words[$i]]);
			$jumlah_df2[$words[$i]]=1;
			}	
		else {
			$jumlah_tf2[$words[$i]]=0;
			$jumlah_df2[$words[$i]]=0;
			}	
		}

	$jumlah_tf3=$bag_of_words;
	$jumlah_df3=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		if (array_key_exists($words[$i],$tfraw3)){
			$jumlah_tf3[$words[$i]]=1+log10($tfraw3[$words[$i]]);
			$jumlah_df3[$words[$i]]=1;
			}	
		else {
			$jumlah_tf3[$words[$i]]=0;
			$jumlah_df3[$words[$i]]=0;
			}	
		} 		

	$jumlah_tf4=$bag_of_words;
	$jumlah_df4=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		if (array_key_exists($words[$i],$tfraw4)){
			$jumlah_tf4[$words[$i]]=1+log10($tfraw4[$words[$i]]);
			$jumlah_df4[$words[$i]]=1;
			}	
		else {
			$jumlah_tf4[$words[$i]]=0;
			$jumlah_df4[$words[$i]]=0;
			}	
		} 
		
	$jumlah_tf5=$bag_of_words;
	$jumlah_df5=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		if (array_key_exists($words[$i],$tfraw5)){
			$jumlah_tf5[$words[$i]]=1+log10($tfraw5[$words[$i]]);
			$jumlah_df5[$words[$i]]=1;
			}	
		else {
			$jumlah_tf5[$words[$i]]=0;
			$jumlah_df5[$words[$i]]=0;
			}	
		} 	
		
	$jumlah_tf6=$bag_of_words;
	$jumlah_df6=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		if (array_key_exists($words[$i],$tfraw6)){
			$jumlah_tf6[$words[$i]]=1+log10($tfraw6[$words[$i]]);
			$jumlah_df6[$words[$i]]=1;
			}	
		else {
			$jumlah_tf6[$words[$i]]=0;
			$jumlah_df6[$words[$i]]=0;
			}	
		} 	
		
	$jumlah_tf_query=array();
	$jumlah_tf_query=$bag_of_words;
	$jumlah_df_query=array();
	$jumlah_df_query=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		if (array_key_exists($words[$i],$tfrawq)){
			$jumlah_tf_query[$words[$i]]=1+log10($tfrawq[$words[$i]]);
			$jumlah_df_query[$words[$i]]=1;
			}	
		else {
			$jumlah_tf_query[$words[$i]]=0;
			$jumlah_df_query[$words[$i]]=0;
			}	
		} 
	$jumlah_df_semua=$bag_of_words;
	for($i=0;$i<count($words);$i++){
			$jumlah_df_semua[$words[$i]]=$jumlah_df1[$words[$i]]+$jumlah_df2[$words[$i]]+$jumlah_df3[$words[$i]]+$jumlah_df4[$words[$i]]+$jumlah_df5[$words[$i]]+$jumlah_df6[$words[$i]]+$jumlah_df_query[$words[$i]];
		} 
	//========================================================= MODIFIED TF IDF =======================================================

	$tfrawidf_1=$bag_of_words;
	$tfrawidf_2=$bag_of_words;
	$tfrawidf_3=$bag_of_words;
	$tfrawidf_4=$bag_of_words;
	$tfrawidf_5=$bag_of_words;
	$tfrawidf_6=$bag_of_words;
	$tfrawidf_query=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		if ($jumlah_df_semua[$words[$i]]!=0){
		$tfrawidf_1[$words[$i]]=$jumlah_tf1[$words[$i]]*log10(6/$jumlah_df_semua[$words[$i]]);
		$tfrawidfsq_1[$words[$i]]=$tfrawidf_1[$words[$i]]*$tfrawidf_1[$words[$i]];
		$tfrawidf_2[$words[$i]]=$jumlah_tf2[$words[$i]]*log10(6/$jumlah_df_semua[$words[$i]]);
		$tfrawidfsq_2[$words[$i]]=$tfrawidf_2[$words[$i]]*$tfrawidf_2[$words[$i]];
		$tfrawidf_3[$words[$i]]=$jumlah_tf3[$words[$i]]*log10(6/$jumlah_df_semua[$words[$i]]);
		$tfrawidfsq_3[$words[$i]]=$tfrawidf_3[$words[$i]]*$tfrawidf_3[$words[$i]];
		$tfrawidf_4[$words[$i]]=$jumlah_tf4[$words[$i]]*log10(6/$jumlah_df_semua[$words[$i]]);
		$tfrawidfsq_4[$words[$i]]=$tfrawidf_4[$words[$i]]*$tfrawidf_4[$words[$i]];
		$tfrawidf_5[$words[$i]]=$jumlah_tf5[$words[$i]]*log10(6/$jumlah_df_semua[$words[$i]]);
		$tfrawidfsq_5[$words[$i]]=$tfrawidf_5[$words[$i]]*$tfrawidf_5[$words[$i]];
		$tfrawidf_6[$words[$i]]=$jumlah_tf6[$words[$i]]*log10(6/$jumlah_df_semua[$words[$i]]);
		$tfrawidfsq_6[$words[$i]]=$tfrawidf_6[$words[$i]]*$tfrawidf_6[$words[$i]];
		$tfrawidf_query[$words[$i]]=$jumlah_tf_query[$words[$i]]*log10(6/$jumlah_df_semua[$words[$i]]);
		$tfrawidfsq_query[$words[$i]]=$tfrawidf_query[$words[$i]]*$tfrawidf_query[$words[$i]];
		}
		else{
		$tfrawidf_1[$words[$i]]=0;
		$tfrawidfsq_1[$words[$i]]=0;
		$tfrawidf_2[$words[$i]]=0;
		$tfrawidfsq_2[$words[$i]]=0;
		$tfrawidf_3[$words[$i]]=0;
		$tfrawidfsq_3[$words[$i]]=0;
		$tfrawidf_4[$words[$i]]=0;
		$tfrawidfsq_4[$words[$i]]=0;
		$tfrawidf_5[$words[$i]]=0;
		$tfrawidfsq_5[$words[$i]]=0;
		$tfrawidf_6[$words[$i]]=0;
		$tfrawidfsq_6[$words[$i]]=0;
		}
	}
	$sum_of_tfrawidfsq_1=array_sum($tfrawidfsq_1);
	$root_of_tfrawidfsq_1=sqrt($sum_of_tfrawidfsq_1);
	$sum_of_tfrawidfsq_2=array_sum($tfrawidfsq_2);
	$root_of_tfrawidfsq_2=sqrt($sum_of_tfrawidfsq_2);
	$sum_of_tfrawidfsq_3=array_sum($tfrawidfsq_3);
	$root_of_tfrawidfsq_3=sqrt($sum_of_tfrawidfsq_3);
	$sum_of_tfrawidfsq_4=array_sum($tfrawidfsq_4);
	$root_of_tfrawidfsq_4=sqrt($sum_of_tfrawidfsq_4);
	$sum_of_tfrawidfsq_5=array_sum($tfrawidfsq_5);
	$root_of_tfrawidfsq_5=sqrt($sum_of_tfrawidfsq_5);
	$sum_of_tfrawidfsq_6=array_sum($tfrawidfsq_6);
	$root_of_tfrawidfsq_6=sqrt($sum_of_tfrawidfsq_6);
	$sum_of_tfrawidfsq_query=array_sum($tfrawidfsq_query);
	$root_of_tfrawidfsq_query=sqrt($sum_of_tfrawidfsq_query);
	
	$tfidf_1=$bag_of_words;
	$tfidf_2=$bag_of_words;
	$tfidf_3=$bag_of_words;
	$tfidf_4=$bag_of_words;
	$tfidf_5=$bag_of_words;
	$tfidf_6=$bag_of_words;
	$tfidf_query=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		$tfidf_1[$words[$i]]=$tfrawidf_1[$words[$i]]/$root_of_tfrawidfsq_1;
		$tfidf_2[$words[$i]]=$tfrawidf_2[$words[$i]]/$root_of_tfrawidfsq_2;
		$tfidf_3[$words[$i]]=$tfrawidf_3[$words[$i]]/$root_of_tfrawidfsq_3;
		$tfidf_4[$words[$i]]=$tfrawidf_4[$words[$i]]/$root_of_tfrawidfsq_4;
		$tfidf_5[$words[$i]]=$tfrawidf_5[$words[$i]]/$root_of_tfrawidfsq_5;
		$tfidf_6[$words[$i]]=$tfrawidf_6[$words[$i]]/$root_of_tfrawidfsq_6;
		$tfidf_query[$words[$i]]=$tfrawidf_query[$words[$i]]/$root_of_tfrawidfsq_query;
		}
	//============================================== X * Di =========================================
	$tfidfsq_1=$bag_of_words;
	$tfidfsq_2=$bag_of_words;
	$tfidfsq_3=$bag_of_words;
	$tfidfsq_4=$bag_of_words;
	$tfidfsq_5=$bag_of_words;
	$tfidfsq_6=$bag_of_words;
	$tfidfsq_query=$bag_of_words;
	for($i=0;$i<count($words);$i++){
		$tfidfsq_1[$words[$i]]=$tfidf_1[$words[$i]]*$tfidf_1[$words[$i]];
		$tfidfsq_2[$words[$i]]=$tfidf_2[$words[$i]]*$tfidf_2[$words[$i]];
		$tfidfsq_3[$words[$i]]=$tfidf_3[$words[$i]]*$tfidf_3[$words[$i]];
		$tfidfsq_4[$words[$i]]=$tfidf_4[$words[$i]]*$tfidf_4[$words[$i]];
		$tfidfsq_5[$words[$i]]=$tfidf_5[$words[$i]]*$tfidf_5[$words[$i]];
		$tfidfsq_6[$words[$i]]=$tfidf_6[$words[$i]]*$tfidf_6[$words[$i]];
		}
		
	$query_dok1=$bag_of_words;
	$query_dok2=$bag_of_words;
	$query_dok3=$bag_of_words;
	$query_dok4=$bag_of_words;
	$query_dok5=$bag_of_words;
	$query_dok6=$bag_of_words;
	for($i=0;$i<count($words);$i++){
			$query_dok1[$words[$i]]=$tfidf_query[$words[$i]]*$tfidf_1[$words[$i]];
			$query_dok2[$words[$i]]=$tfidf_query[$words[$i]]*$tfidf_2[$words[$i]];
			$query_dok3[$words[$i]]=$tfidf_query[$words[$i]]*$tfidf_3[$words[$i]];
			$query_dok4[$words[$i]]=$tfidf_query[$words[$i]]*$tfidf_4[$words[$i]];
			$query_dok5[$words[$i]]=$tfidf_query[$words[$i]]*$tfidf_5[$words[$i]];
			$query_dok6[$words[$i]]=$tfidf_query[$words[$i]]*$tfidf_5[$words[$i]];
			}

	$sum_of_query_dok1	=	array_sum($query_dok1);
	$sum_of_query_dok2	=	array_sum($query_dok2);
	$sum_of_query_dok3	=	array_sum($query_dok3); 
	$sum_of_query_dok4	=	array_sum($query_dok4); 
	$sum_of_query_dok5	=	array_sum($query_dok5); 
	$sum_of_query_dok6	=	array_sum($query_dok6); 

	//=========================================== RESULT =================================================
	
	$sum_of_tfidfsq_1=array_sum($tfidfsq_1);
	$sum_of_tfidfsq_2=array_sum($tfidfsq_2);
	$sum_of_tfidfsq_3=array_sum($tfidfsq_3);
	$sum_of_tfidfsq_4=array_sum($tfidfsq_4);
	$sum_of_tfidfsq_5=array_sum($tfidfsq_5);
	$sum_of_tfidfsq_6=array_sum($tfidfsq_6);
	$sum_of_tfidfsq_query=array_sum($tfidfsq_query);
	$root_of_tfidfsq_1=sqrt($sum_of_tfidfsq_1);
	$root_of_tfidfsq_2=sqrt($sum_of_tfidfsq_2);
	$root_of_tfidfsq_3=sqrt($sum_of_tfidfsq_3);
	$root_of_tfidfsq_4=sqrt($sum_of_tfidfsq_4);
	$root_of_tfidfsq_5=sqrt($sum_of_tfidfsq_5);
	$root_of_tfidfsq_6=sqrt($sum_of_tfidfsq_6);
	$root_of_tfidfsq_query=sqrt($sum_of_tfidfsq_query);
	
	$cossim_of_dok1	=	$sum_of_query_dok1 / ($root_of_tfidfsq_1 * $root_of_tfidfsq_query);
	$cossim_of_dok2	=	$sum_of_query_dok2 / ($root_of_tfidfsq_2 * $root_of_tfidfsq_query);
	$cossim_of_dok3	=	$sum_of_query_dok3 / ($root_of_tfidfsq_3 * $root_of_tfidfsq_query);
	$cossim_of_dok4	=	$sum_of_query_dok4 / ($root_of_tfidfsq_4 * $root_of_tfidfsq_query);
	$cossim_of_dok5	=	$sum_of_query_dok5 / ($root_of_tfidfsq_5 * $root_of_tfidfsq_query);
	$cossim_of_dok6	=	$sum_of_query_dok6 / ($root_of_tfidfsq_6 * $root_of_tfidfsq_query);
	$cossim=array($cossim_of_dok1,$cossim_of_dok2,$cossim_of_dok3,$cossim_of_dok4,$cossim_of_dok5,$cossim_of_dok6);
	$dok=array('Finance',' Finance','Sport','Politic', 'Daily  News',' Daily News');
	$class_cossim=array_combine($dok,$cossim);
	arsort($class_cossim);
	foreach ($class_cossim as $word=>$count){
		$word_rank[]=$word;		
		}	
	
	//=====================================================================================================

	echo "<div class='col-md-12'>";	
	echo"<h4>Documents</h4>";
	echo "<table border='1'>";
	echo "<th>Document 1</th><th>Document 2</th><th>Document 3</th><th>Document 4</th><th>Document 5</th><th>Document 6</th><th>Document Query</th>";
		echo "<tr><td>$sentence</td><td>$sentence_2</td><td>$sentence_3</td><td>$sentence_4</td><td>$sentence_5</td><td>$sentence_6</td><td>$sentence_q</td></tr>";
	echo"</table>";
	echo"</div>";
	

	echo "<div class='col-md-12'>";	
	echo"<h4>Processed Documents</h4>";
	echo "<table border='1'>";
	echo "<th>Document 1</th><th>Document 2</th><th>Document 3</th><th>Document 4</th><th>Document 5</th><th>Document 6</th><th>Document Query</th>";
		echo "<tr><td>$output</td><td>$output_2</td><td>$output_3</td><td>$output_4</td><td>$output_5</td><td>$output_6</td><td>$output_q</td></tr>";
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-12'>";	
		echo "</div>";
	// output a representation of the inverted index
	echo "<div class='col-md-12'>";	
	echo"<h4>Inverted Index</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>Index</th>";
	foreach($corpus_terms AS $term => $doc_locations) {
		echo "<tr><td> $term</td><td>";
		foreach($doc_locations AS $doc_location){ 
			echo "{".$doc_location[DOC_ID].", ".$doc_location[TERM_POSITION]."} ";}
			echo "</td></tr>";
		}
	echo"</table>";
	echo"</div>";

	echo "<div class='col-md-3'>";	
	echo"<h4>TF Raw Document 1</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF Raw</th>";
	foreach ($jumlah_tf1 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF Raw Document 2</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF Raw</th>";
	foreach ($jumlah_tf2 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF Raw Document 3</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF Raw</th>";
	foreach ($jumlah_tf3 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF Raw Document 4</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF Raw</th>";
	foreach ($jumlah_tf4 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF Raw Document 5</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF Raw</th>";
	foreach ($jumlah_tf5 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF Raw Document 6</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF Raw</th>";
	foreach ($jumlah_tf6 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF Raw Document Query</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF Raw</th>";
	foreach ($jumlah_tf_query as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";

		echo "<div class='col-md-12'>";	
		echo "</div>";
	
	echo "<div class='col-md-3'>";	
	echo"<h4>DF Document 1</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>DF</th>";
	foreach ($jumlah_df_semua as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";

	echo "<div class='col-md-12'>";	
		echo "</div>";
	
	echo "<div class='col-md-3'>";	
	echo"<h4>TF-IDF Document 1</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF-IDF</th>";
	foreach ($tfidf_1 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF-IDF Document 2</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF-IDF</th>";
	foreach ($tfidf_2 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF-IDF Document 3</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF-IDF</th>";
	foreach ($tfidf_3 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF-IDF Document 4</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF-IDF</th>";
	foreach ($tfidf_4 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF-IDF Document 5</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF-IDF</th>";
	foreach ($tfidf_5 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF-IDF Document 6</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF-IDF</th>";
	foreach ($tfidf_6 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-3'>";	
	echo"<h4>TF-IDF Document Query</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>TF-IDF</th>";
	foreach ($tfidf_query as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";

	echo "<div class='col-md-12'>";	
		echo "</div>";
	
	echo "<div class='col-md-4'>";	
	echo"<h4>TF-IDF Document Query * TF-IDF Document 1</h4>";
	echo "<table border='1'>";
	echo "<th>Term</th><th>Result</th>";
	foreach ($query_dok1 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	echo "<div class='col-md-4'>";	
	echo"<h4>TF-IDF Document Query * TF-IDF Document 2</h4>";
	echo "<table border='1' >";
	echo "<th>Term</th><th>Result</th>";
	foreach ($query_dok2 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
		echo "<div class='col-md-4'>";	
	echo"<h4>TF-IDF Document Query * TF-IDF Document 3</h4>";
	echo "<table border='1' >";
	echo "<th>Term</th><th>Result</th>";
	foreach ($query_dok3 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
		echo "<div class='col-md-4'>";	
	echo"<h4>TF-IDF Document Query * TF-IDF Document 4</h4>";
	echo "<table border='1' >";
	echo "<th>Term</th><th>Result</th>";
	foreach ($query_dok4 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
		echo "<div class='col-md-4'>";	
	echo"<h4>TF-IDF Document Query * TF-IDF Document 5</h4>";
	echo "<table border='1' >";
	echo "<th>Term</th><th>Result</th>";
	foreach ($query_dok5 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
		echo "<div class='col-md-4'>";	
	echo"<h4>TF-IDF Document Query * TF-IDF Document 6</h4>";
	echo "<table border='1' >";
	echo "<th>Term</th><th>Result</th>";
	foreach ($query_dok6 as $word=>$count){
		echo "<tr><td> $word</td><td> $count</td></tr>";
		}
	echo"</table>";
	echo"</div>";
	
	
	echo"<div class='col-md-12'><center><p>Final Result</p></center></div>";
	echo "<div class='col-md-4'>";	
	echo"<p>Query Document to Document 1: $cossim_of_dok1</p>";
	echo"</div>";
	echo "<div class='col-md-4'>";	
	echo"<p>Query Document to Document 2: $cossim_of_dok2</p>";
	echo"</div>";
	echo "<div class='col-md-4'>";	
	echo"<p>Query Document to Document 3: $cossim_of_dok3</p>";
	echo"</div>";
	echo "<div class='col-md-4'>";	
	echo"<p>Query Document to Document 4: $cossim_of_dok4</p>";
	echo"</div>";
	echo "<div class='col-md-4'>";	
	echo"<p>Query Document to Document 5: $cossim_of_dok5</p>";
	echo"</div>";
	echo "<div class='col-md-4'>";	
	echo"<p>Query Document to Document 6: $cossim_of_dok6</p>";
	echo"</div>";

	echo "<div class='col-md-12'><center><h4>So based on Modified TF IDF KNN computation, Document Query is classified into $word_rank[0]</h4></center></div>";

	}
	?>

	<div class="col-md-12">
	<center>
	<p>
	Developed by: <br />
	Dinda Novitasari - 115060800111007 <br />
	<a href="http://dindanovitasari.com">dindanovitasari.com</a><br /><br />

	With: <br />
	Sastrawi - High quality PHP library for stemming Indonesian Language (Bahasa)<br />
	<a href="http://github.com/sastrawi/sastrawi">github.com/sastrawi/sastrawi</a><br />	
	Count TF<br />
	<a href="http://stackoverflow.com/questions/2984786/php-sort-and-count-instances-of-words-in-a-given-string">php: sort and count instances of words in a given string</a><br />
	Inverted Index<br />
	<a href="http://www.phpmath.com/home?op=cat&cid=15">PHPMath - Information Retrieval Class</a><br /><br />
	
	From:<br />
	KNN with TF-IDF Based Framework for Text Categorization<br />
	Bruno Trstenjaka,Sasa Mikac, Dzenana Donko<br />
	24th DAAAM International Symposium on Intelligent Manufacturing and Automation, 2013<br />
	<a href="http://www.sciencedirect.com/science/article/pii/S1877705814003750">Journal</a></p>
    </center>
	</div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
	<noscript>activate javascript</noscript>
</body>
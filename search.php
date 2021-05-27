<?PHP 
include "src/dbms.php";
function regorbuilder($keyword)
{
$keyword=str_replace(" ","|",$keyword);
return "REGEXP(\"/{$keyword}/i\")";
}

function search($query,$page)
{
	$q=explode(":",$query);
    $term=end($q);
    $sql="";
	if(preg_match("/category:/i",$query)){
		$sql.=sprintf("SELECT count(cid) as count  from category where lower(category)=\"%s\"",strtolower($term));
		$filter=1;
	}
		
	else if(preg_match("/tag:/i",$query)){
		$sql.=sprintf("SELECT count(cid) as count from taglist where lower(tag)=\"%s\"",strtolower($term));
		$filter=2;
	}
	else 
	{
        
		$sql.=sprintf("(SELECT count(cid) as count from taglist where lower(tag) %s) UNION
					   (SELECT count(cid) as count from category where lower(category) %s ) UNION
					   (SELECT count(courseid) as count from course where lower(coursetitle) %s) UNION
					   (SELECT count(courseid) as count from course where lower(description) %s)",regorbuilder($term),regorbuilder($term),regorbuilder($term),regorbuilder($term));
		$filter=3;
	}
	//echo $sql."\n";
	$_SERVER["db"]->query($sql);
	$total_records=$_SERVER["db"]->data();
	$_SESSION["total_pages"]=ceil($total_records/9);
	$_SESSION["page"]=$page;
echo $total_records;
}

search("category:cbse",1);
echo "\n";
search("tag:physics",1);
echo "\n";  
search("chemistry XII XI ",1);
?>
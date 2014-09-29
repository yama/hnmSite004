<?php
class NP_hnmTopitem extends NucleusPlugin{
	function getName(){ return 'hnm Top Item'; }// プラグインの名前
	function getAuthor(){ return 'yotaka'; }// プラグインの作者
	function getURL(){ return 'http://blog.hanamarl.com/hnmSkin.php'; }// プラグインのサイトURL
	function getVersion(){ return '1.0'; }// プラグインのバージョン
	function getDescription(){ return 'hnmSiteスキン専用プラグインです他のスキンでの動作チェックは行っておりません。'; }
	function supportsFeature($what){if($what == 'SqlTablePrefix'){return 1;}else{return 0;}}

	function doSkinVar($skinType, $items=''){
		global $blog,$blogid,$catid,$itemid,$MYSQL_PREFIX ;

		if($items=="catCount"){
			$cCnt = mysql_num_rows(sql_query('SELECT * FROM '.sql_table('category').' WHERE cblog='.intval($blogid)));
			if($cCnt<3){$cCnt=3;}
			elseif($cCnt>8){$cCnt=8;}
			
			echo " class=\"cCnt".$cCnt."\"" ;
			
		}else{
				
	//ショートネームを呼び出す
		$sName = quickQuery('SELECT bshortname as result FROM '.sql_table('blog').' WHERE bnumber='.intval($blogid));


	//サポートブログのIDを抽出
		$pgId = getBlogIDFromName($sName."Pg");


	//サポートブログが存在すれば処理を続ける
		if($pgId){


	//sqlの共通部分作成
			$query = "SELECT ibody as result FROM ".sql_table('item')." WHERE iblog=".$pgId." AND ";


	//アイテムIDがあれば探す
			if(is_int($items)){
				$res = quickQuery($query."inumber=".$items." LIMIT 1");
			
				if(!$res){$res = "<!--hnmTopitem:itemid".$items."-->";}


	//特定ワードでなければ
			}elseif($items && $items != "css" && $items != "kbn"){
				$res = quickQuery($query."ititle LIKE '%".$items."%' LIMIT 1");
				
				if($res){$res = "<div id=\"".$items."\">\n".$res."\n</div>\n";}
				else{$res = "<!--hnmTopitem:".$items."-->";}
			
			
	//特定ワードか、トップページの場合
			}else{
			
				if($items){//トップページ用アイテムでなければ
					$query.= "ititle LIKE '%";
					$query2 = "%' LIMIT 1";
				
				}else{//トップページ用アイテムなら
					$query.= "ititle='";
					$query2 = "' LIMIT 1";
				}
			
				if($catid){//トップページでなければ、
					$items1 = "c".$catid.$items ;
					
					if($itemid){//個別アイテムであれば
						$items2 = "i".$itemid.$items ;
						$items2 = quickQuery($query.$items2.$query2);
					}
					
				}else{//トップページであれば、
					$items1 = "c0".$items ;
				}
			
				$res = quickQuery($query.$items1.$query2);
				if($items2){$res.= "\n".$items2 ;}
			
				if(!$res){$res = "<!--hnmTopitem:".$items1."-->";}
			}
			echo $res ;
		}
		}
	}
}
?>
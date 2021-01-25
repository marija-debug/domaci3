<?php
require 'flight/Flight.php';
//ukoliko se kreirao fajl jsonindent.php
require 'jsonindent.php';

Flight::register('db', 'Database', array('rest'));
$json_podaci = file_get_contents("php://input");
Flight::set('json_podaci', $json_podaci );

Flight::route('/', function(){
    echo 'hello world!';
});

Flight::route('GET /predstave.json', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("predstava", "*", null, null, null, null, null);
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
    $json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /predstave/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	test_input($id);
	$db->select("predstava", "*", null, null, null, "predstava.id = ".$id, null);
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
    $json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /rezervacije/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	test_input($id);
	$db->selectJoinTwice("rezervacije", "rezervacije.id, DATE_FORMAT(rezervacije.datum, '%d. %M %Y.') as datum, predstava.naziv as naziv, predstava.cena as cena, sala.nazivSale as sala, rezervacije.sediste as sediste, predstava.trajanje as trajanje, predstava.zanr as zanr", 
	"predstava", "predstavaId", "id", "sala", "salaId", "id", 
	"rezervacije.korisnikId = ".$id, "rezervacije.datum, rezervacije.sediste");
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
    $json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /rezervacija/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	test_input($id);
	$db->selectJoinTwice("rezervacije", "rezervacije.id, DATE_FORMAT(rezervacije.datum, '%d. %M %Y.') as datum, predstava.naziv as naziv, sala.nazivSale as sala, rezervacije.sediste as sediste, predstava.trajanje as trajanje, predstava.zanr as zanr", 
	"predstava", "predstavaId", "id", "sala", "salaId", "id", 
	"rezervacije.korisnikId = ".$id, "rezervacije.datum, rezervacije.sediste");
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
    $json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /predstave/@id/@date.json', function($id, $date){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	test_input($id);
	test_input($date);
	$db->select("izvodjenja", "salaId", null, null, null, "predstavaId = ".$id." and datum = '".$date."'", null);

	$red=$db->getResult()->fetch_object();
	$sala = $red->salaId;
	
	$db_pomocna=new Database("rest");
	$db_pomocna->select("sedista", "id, salaId", null, null, null, "id not in (select sediste from rezervacije where predstavaId = ".$id." and salaId =".$sala." and datum = '".$date."') and salaId = ".$sala, null);
		
	$niz=array();
	while ($r=$db_pomocna->getResult()->fetch_object()){
		$niz[] = $r;
	}
	
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
    $json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /izvodjenja/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	test_input($id);
	$db->select("izvodjenja", "DATE_FORMAT(datum, '%d. %M %Y.') as formDatum, datum", null, null, null, "predstavaId = ".$id." and datum > 'NOW()'", "datum");
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
    $json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /novosti.json', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select();
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
    $json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /novosti/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("novosti", "*", "kategorije", "kategorija_id", "id", "novosti.id = ".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($red,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /kategorije.json', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("kategorije", "*", null, null, null, null, null);
	$niz=array();
	$i=0;
	while ($red=$db->getResult()->fetch_object()){
		
		$niz[$i]["id"] = $red->id;
		$niz[$i]["kategorija"] = $red->kategorija;
		$db_pomocna=new Database("rest");
		$db_pomocna->select("novosti", "*", null, null, null, "novosti.kategorija_id = ".$red->id, null);
		while ($red_pomocna=$db_pomocna->getResult()->fetch_object()){
			$niz[$i]["novosti"][]=$red_pomocna;
		}
		$i++;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});

Flight::route('GET /kategorije/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("kategorije", "*", null, null, null, "kategorije.id = ".$id, null);
	$niz=array();
	
	$red=$db->getResult()->fetch_object();
		
		$niz["id"] = $red->id;
		$niz["kategorija"] = $red->kategorija;
		$db_pomocna=new Database("rest");
		$db_pomocna->select("novosti", "*", null, null, null, "novosti.kategorija_id = ".$red->id, null);
		while ($red_pomocna=$db_pomocna->getResult()->fetch_object()){
		$niz["novosti"][]=$red_pomocna;
		}

	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;


});

Flight::route('POST /novosti', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	return false;
	} else {
	if (!property_exists($podaci,'naslov')||!property_exists($podaci,'tekst')||!property_exists($podaci,'kategorija_id')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
            foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->insert("novosti", "naslov, tekst, kategorija_id, datumvreme", array($podaci_query["naslov"], $podaci_query["tekst"], $podaci_query["kategorija_id"], 'NOW()'))){
				$odgovor["poruka"] = "Novost je uspešno ubačena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	
	}
);

Flight::route('POST /predstave', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	return false;
	} else {
	if (!property_exists($podaci,'naziv')||!property_exists($podaci,'zanr')||!property_exists($podaci,'trajanje')||!property_exists($podaci,'opis')||!property_exists($podaci,'cena')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
            foreach ($podaci as $k=>$v){
				test_input($v);
				if($k != "trajanje" || $k != "cena"){
					$v = "'".$v."'";
				}
				$podaci_query[$k] = $v;
			}
			if ($db->insert("predstava", "naziv, zanr, trajanje, opis, cena", array($podaci_query["naziv"], $podaci_query["zanr"], $podaci_query["trajanje"], $podaci_query["opis"], $podaci_query["cena"]))){
				$odgovor["poruka"] = "Predstava je uspešno ubačena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju predstave";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	
	}
);

Flight::route('POST /rezervacija', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	return false;
	} else {
	if (!property_exists($podaci,'korisnikId')||!property_exists($podaci,'predstavaId')||!property_exists($podaci,'salaId')||!property_exists($podaci,'sediste')||!property_exists($podaci,'datum')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
            foreach ($podaci as $k=>$v){
				test_input($v);
				// if($k == "sediste" || $k == "datum"){
				// 	$v = "'".$v."'";
				// }
				if(!is_numeric($v)){
					$v = "'".$v."'";
				}
				$podaci_query[$k] = $v;
			}
			if ($db->insert("rezervacije", "salaId, sediste, datum, predstavaId, korisnikId", array($podaci_query["salaId"], $podaci_query["sediste"], $podaci_query["datum"], $podaci_query["predstavaId"], $podaci_query["korisnikId"]))){
				$odgovor["poruka"] = "Rezervacija je uspešno sačuvana";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri čuvanju rezervacije";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	
	}
);

Flight::route('POST /register', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	return false;
	} else {
	if (!property_exists($podaci,'username')||!property_exists($podaci,'password')||!property_exists($podaci,'fullName')||!property_exists($podaci,'email')||!property_exists($podaci,'status')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
            foreach ($podaci as $k=>$v){
				test_input($v);
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}

			$db_pomocna=new Database("rest");
			$db_pomocna->select("korisnik", "username, email", null, null, null, "korisnik.status = 'user'", null);
			while ($r=$db_pomocna->getResult()->fetch_object()){
				if($r->username == $podaci->username){
					$odgovor["poruka"] = "Username vec postoji";
					$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
					echo $json_odgovor;
					return false;
				}
				if($r->email == $podaci->email){
					$odgovor["poruka"] = "Email vec postoji";
					$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
					echo $json_odgovor;
					return false;
				}
			}
			
	
			if ($db->insert("korisnik", "username, password, imePrezime, email, status", array($podaci_query["username"], $podaci_query["password"], $podaci_query["fullName"], $podaci_query["email"], $podaci_query["status"]))){
				$odgovor["poruka"] = "Korisnik je uspešno registrovan";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri registraciji korisnika";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	
	}
);

Flight::route('POST /kategorije', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	} else {
	if (!property_exists($podaci,'kategorija')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->insert("kategorije", "kategorija", array($podaci_query["kategorija"]))){
				$odgovor["poruka"] = "Kategorija je uspešno ubačena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
                echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	


});

Flight::route('PUT /novosti/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	} else {
	if (!property_exists($podaci,'naslov')||!property_exists($podaci,'tekst')||!property_exists($podaci,'kategorija_id')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->update("novosti", $id, array('naslov','tekst','kategorija_id'),array($podaci->naslov, $podaci->tekst,$podaci->kategorija_id))){
				$odgovor["poruka"] = "Novost je uspešno izmenjena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri izmeni novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
                return false;
			}
	}
	}	
});

Flight::route('PUT /predstave/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	} else {
	if (!property_exists($podaci,'naziv')||!property_exists($podaci,'zanr')||!property_exists($podaci,'trajanje')||!property_exists($podaci,'opis') ||!property_exists($podaci,'cena')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				test_input($v);
				if($k != "trajanje" || $k != "cena"){
					$v = "'".$v."'";
				}
				$podaci_query[$k] = $v;
			}
			if ($db->update("predstava", $id, array('naziv','zanr','trajanje','opis','cena'),array($podaci->naziv, $podaci->zanr,$podaci->trajanje,$podaci->opis,$podaci->cena))){
				$odgovor["poruka"] = "Predstava je uspešno izmenjena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri izmeni predstave";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
                return false;
			}
	}
	}	
});


Flight::route('PUT /kategorije/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	} else {
	if (!property_exists($podaci,'kategorija')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->update("kategorije", $id, array('kategorija'),array($podaci->kategorija))){
				$odgovor["poruka"] = "Kategorija je uspešno izmenjena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri izmeni kategorije";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	

});

Flight::route('DELETE /novosti/@id', function($id){
    header ("Content-Type: application/json; charset=utf-8");
    $db = Flight::db();
    if ($db->delete("novosti", array("id"),array($id))){
            $odgovor["poruka"] = "Novost je uspešno izbrisana";
            $json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
            return false;
    } else {
            $odgovor["poruka"] = "Došlo je do greške prilikom brisanja novosti";
            $json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
            return false;
    
    }		
            
});

Flight::route('DELETE /kategorije/@id', function($id){
    header ("Content-Type: application/json; charset=utf-8");
    $db = Flight::db();
    if ($db->delete("kategorije", array("id"),array($id))){
            $odgovor["poruka"] = "Kategorija je uspešno izbrisana";
            $json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
            return false;
    } else {
            $odgovor["poruka"] = "Došlo je do greške prilikom brisanja kategorije";
            $json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
            return false;
    
    }		


});

Flight::route('DELETE /predstave/@id', function($id){
    header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	test_input($id);
    if ($db->delete("predstava", array("id"),array($id))){
            $odgovor["poruka"] = "Predstava je uspešno izbrisana";
            $json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
            return false;
    } else {
            $odgovor["poruka"] = "Došlo je do greške prilikom brisanja predstave";
            $json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
            return false;
    
    }		
            
});

Flight::route('GET /novosti.json', function(){});
Flight::route('GET /novosti/@id.json', function($id){});
Flight::route('GET /kategorije.json', function(){});
Flight::route('GET /kategorije/@id.json', function($id){});
Flight::route('POST /novosti', function(){});
Flight::route('POST /kategorije', function(){});
Flight::route('PUT /novosti/@id', function($id){});
Flight::route('PUT /kategorije/@id', function($id){});
Flight::route('DELETE /novosti/@id', function($id){});
Flight::route('DELETE /kategorije/@id', function($id){});

function test_input($data){
	$data = trim($data);
	$data = htmlspecialchars($data);
	$data = stripslashes($data);
}


Flight::start();

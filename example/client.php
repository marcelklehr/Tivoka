<pre>
<?php
include('../include.php');

$target = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/server.php';
$request = Tivoka::createRequest('1', 'demo.substract', array(43,1));
$greeting = Tivoka::createRequest('2', 'demo.sayHello');

Tivoka::connect($target)->send($request, $greeting);


/*
 * Display the Results...
 */

/*
 * Display reponse
 */
if($request->response->isError())
{
	// an error occured
	var_dump($request->response->error);
	var_dump($request->response->errorMessage);
	var_dump($request->response->data);
}else
{
	// the result
	var_dump($request->response->result);
	var_dump($greting->response->result);
}
?>
</pre>
<pre><?php

/*
Ya se que es un codigo de mierda, pero a alguien le servira

el codigo sigue la lógica de un mal jugador de dominó venezolano

esta calcular_resultado() y esta calcular_resultado_trancado() fueron c readas por copilot.

este codigo representa el flujo de una partida de domino entre cuatro jugadores.

el juego no se ejecuta de manera estrategica sino de manera logica, solo busca una jugada lógica en los aspectos funcionales del juego
basicamente la primera ficha que vea que cuadre la asignará.

para mayor aleatoriedad en cada iteracion el array de fichas disponibles para cada jugador se desordena

*/

class Domino
{
	const MIN_PUNTOS 	= 100; 
	/*-------------------------------------------*/
	public $fichas 		= []; /* Genero las fichas de dominó, una vez sean repartidas el array queda vacio */
	public $jugadores 	= []; /* array de cuatro jugadores con sus respectivas siete fichas */
	public $puntos 		= [];
	public $quien_sale	= NULL;
	public $mesa		= [];
	/*-------------------------------------------*/

	public function Revolver()
	{
		for( $i = 0; $i <= 6; $i++ )
		{
			for( $j = $i; $j <= 6; $j++ )
			{
				$this->fichas[] = [ $i , $j ]; 
			}
		}

		shuffle( $this->fichas );
	}

	public function Repartir()
	{
		for( $i = 0; $i <= 3; $i++ )
		{
			for( $j = 0; $j <= 6; $j++ )
			{
				$this->jugadores[$i][$j] = array_pop( $this->fichas );
			}
		}
	}

	public function Partida( $index_partida )
	{
		$status_partida = true;

		if( $index_partida == 0 )
		{
			$this->quien_sale 	= $this->Busco_La_Cochina()['jugador']; /* index del jugador que posee la cochina */
			$ficha_inicio		= $this->Busco_La_Cochina()['ficha']; /* index de la cochina */
			$this->mesa[]		= $this->jugadores[$this->quien_sale][$ficha_inicio];
			
			echo "1 - Turno Jugador --------------------------------------------- #{$this->quien_sale}\r\n";
			echo "Jugada Inicial [{$this->jugadores[$this->quien_sale][$ficha_inicio][0]}:{$this->jugadores[$this->quien_sale][$ficha_inicio][1]}]\r\n";

			unset( $this->jugadores[$this->quien_sale][$ficha_inicio] );
		}
		else
		{
			$this->quien_sale 	= ( $this->quien_sale == 3 ? 0 : $this->quien_sale+1 );
			$ficha_inicio		= rand( 0 , 6 );

			echo "1 - Turno Jugador --------------------------------------------- #{$this->quien_sale}\r\n";
			echo "Jugada Inicial [{$this->jugadores[$this->quien_sale][$ficha_inicio][0]}:{$this->jugadores[$this->quien_sale][$ficha_inicio][1]}]\r\n";

			$this->mesa[]		= $this->jugadores[$this->quien_sale][$ficha_inicio];
			unset( $this->jugadores[$this->quien_sale][$ficha_inicio] );
		}

		$turno = $this->quien_sale == 3 ? 0 : $this->quien_sale+1;

		$jugada = 1;

		$p_conteo = 0;

		while( $status_partida == true ):
		
			/*----*/
			$valor_arriba 	= $this->mesa[ array_key_first( $this->mesa ) ][0];
			$valor_abajo 	= $this->mesa[ array_key_last( $this->mesa ) ][1];
			/*----*/
			$jugo = false;
			echo ( $jugada + 1 ) . " - Turno Jugador --------------------------------------------- #{$turno}\r\n";

			shuffle( $this->jugadores[$turno] ); /* agrego un poco de aletoriedad en las fichas en cada iteracion */

			foreach( $this->jugadores[$turno] as $clave_ficha => $ficha_turno ):

				/* si hago que estos dos bloques condicionales se ejecuten en orden aleatorio el juego sera mas natural*/
				/* posiblemente con par de funciones y un asignador aleatorio */

				if( $ficha_turno[0] == $valor_arriba || $ficha_turno[1] == $valor_arriba )
				{
					echo "Jugada Arriba [{$ficha_turno[0]}:{$ficha_turno[1]}]\r\n";
					/*----*/
					if( $ficha_turno[1] == $valor_arriba )
					{
						array_unshift( $this->mesa , $this->jugadores[$turno][$clave_ficha] );
						unset( $this->jugadores[$turno][$clave_ficha] );
						$jugo = true;
						$p_conteo = 0;
						//foreach( $this->mesa as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
						break;
					}
					elseif( $ficha_turno[0] == $valor_arriba )
					{
						array_unshift( $this->mesa , array_reverse( $this->jugadores[$turno][$clave_ficha] ) );
						unset( $this->jugadores[$turno][$clave_ficha] );
						//echo "Ficha volteada\r\n";
						$jugo = true;
						$p_conteo = 0;
						//foreach( $this->mesa as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
						break;
					}
					/*----*/
				}
				elseif( $ficha_turno[0] == $valor_abajo || $ficha_turno[1] == $valor_abajo )
				{
					echo "Jugada Abajo [{$ficha_turno[0]}:{$ficha_turno[1]}]\r\n";
					/*----*/
					if( $ficha_turno[0] == $valor_abajo )
					{
						array_push( $this->mesa , $this->jugadores[$turno][$clave_ficha] );
						unset( $this->jugadores[$turno][$clave_ficha] );
						$jugo = true;
						$p_conteo = 0;
						//foreach( $this->mesa as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
						break;
					}
					elseif( $ficha_turno[1] == $valor_abajo )
					{
						array_push( $this->mesa , array_reverse( $this->jugadores[$turno][$clave_ficha] ) );
						unset( $this->jugadores[$turno][$clave_ficha] );
						//echo "Ficha volteada\r\n";
						$jugo = true;
						$p_conteo = 0;
						//foreach( $this->mesa as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
						break;
					}
					/*----*/
				}

			endforeach;

			if( $jugo == false )
			{
				$p_conteo = $p_conteo+1;
				echo "Pasó :P\r\n";
				//print_r( $this->jugadores );
				//echo "/////////////////////////////\r\n\r\n";
				//print_r( $this->mesa );
			}

			foreach( $this->mesa as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";

			//echo "Fin Turno Jugador ----------------------------------------- #{$turno}\r\n\r\n";

			/* cursores */
			$turno = $turno == 3 ? 0 : $turno+1;
			$jugada++;
			/* cursores */

			if( count( $this->jugadores[0] ) == 0 || count( $this->jugadores[1] ) == 0 || count( $this->jugadores[2] ) == 0 || count( $this->jugadores[3] ) == 0 || $p_conteo >= 4 )
			{
				echo "\r\nTerminó la partida\r\n\r\n";

				if( $p_conteo >= 4 )
				{
					echo "Trancada\r\n\r\n";
					$rest = $this->calcular_resultado_trancado();
					echo "************************\r\n";
					echo "jugador 0 - "; foreach( $this->jugadores[0] as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "jugador 1 - "; foreach( $this->jugadores[1] as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "jugador 2 - "; foreach( $this->jugadores[2] as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "jugador 3 - "; foreach( $this->jugadores[3] as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "Mesa: "; foreach( $this->mesa as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "Equipo {$rest['ganador'][0]} con {$rest['ganador'][1]}\r\n";
					echo "Puntuación {$rest['puntuacion']}\r\n";
					echo "************************\r\n";
				}
				
				if( count( $this->jugadores[0] ) == 0 || count( $this->jugadores[1] ) == 0 || count( $this->jugadores[2] ) == 0 || count( $this->jugadores[3] ) == 0 )
				{
					echo "Ciclo completo\r\n\r\n";
					$rest = $this->calcular_resultado();
					echo "************************\r\n";
					echo "jugador 0 - "; foreach( $this->jugadores[0] as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "jugador 1 - "; foreach( $this->jugadores[1] as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "jugador 2 - "; foreach( $this->jugadores[2] as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "jugador 3 - "; foreach( $this->jugadores[3] as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "Mesa: "; foreach( $this->mesa as $prete ){ echo "[{$prete[0]}:{$prete[1]}]"; } echo "\r\n";
					echo "Equipo {$rest['ganador'][0]} con {$rest['ganador'][1]}\r\n";
					echo "Puntuación {$rest['puntuacion']}\r\n";
					echo "************************\r\n";
				}

				break;
			}

			//if( $jugada == 12 ){ break; } /* mientras programo para no volver locaesta vaina */
		endwhile;

	}

	function Busco_La_Cochina()
	{
	    for($i = 0; $i <= 3; $i++ )
	    {
	        for($j = 0; $j <= 6; $j++ )
	        {
	            if( $this->jugadores[$i][$j][0] == 6 && $this->jugadores[$i][$j][1] == 6 )
	            {
	                return [ 'jugador' => $i, 'ficha' => $j ];
	            }
	        }
	    }
	}

	public function calcular_resultado() {
	  // Asignamos los equipos según las claves del array
	  $equipo1 = array(0, 2); // Clave 0 y clave 2
	  $equipo2 = array(1, 3); // Clave 1 y clave 3
	  // Inicializamos las variables para almacenar el equipo ganador y la puntuación
	  $ganador = null;
	  $puntuacion = 0;
	  // Recorremos el array para buscar el equipo que se quedó sin fichas primero
	  foreach ($this->jugadores as $clave => $fichas) {
	    // Si el array de fichas está vacío, significa que el jugador se quedó sin fichas
	    if (empty($fichas)) {
	      // Comprobamos a qué equipo pertenece el jugador
	      if (in_array($clave, $equipo1)) {
	        // El jugador pertenece al equipo 1, por lo que el equipo 1 es el ganador
	        $ganador = $equipo1;
	      } else {
	        // El jugador pertenece al equipo 2, por lo que el equipo 2 es el ganador
	        $ganador = $equipo2;
	      }
	      // Salimos del bucle, ya que no hace falta seguir buscando
	      break;
	    }
	  }
	  // Si encontramos un equipo ganador, calculamos la puntuación del equipo perdedor
	  if ($ganador != null) {
	    // Recorremos el array para sumar los puntos de las fichas que quedan en la mano del equipo perdedor
	    foreach ($this->jugadores as $clave => $fichas) {
	      // Comprobamos si el jugador pertenece al equipo perdedor
	      if (!in_array($clave, $ganador)) {
	        // El jugador pertenece al equipo perdedor, por lo que sumamos los puntos de sus fichas
	        foreach ($fichas as $ficha) {
	          // Cada ficha es un array de dos elementos, que representan los puntos de cada lado
	          // Sumamos los dos elementos y los añadimos a la puntuación
	          $puntuacion += $ficha[0] + $ficha[1];
	        }
	      }
	    }
	  }
	  // Devolvemos el equipo ganador y la puntuación como un array asociativo
	  return array("ganador" => $ganador, "puntuacion" => $puntuacion);
	}

	public function calcular_resultado_trancado() {
	  // Asignamos los equipos según las claves del array
	  $equipo1 = array(0, 2); // Clave 0 y clave 2
	  $equipo2 = array(1, 3); // Clave 1 y clave 3
	  // Inicializamos las variables para almacenar el equipo ganador, la puntuación y el mínimo
	  $ganador = null;
	  $puntuacion = 0;
	  $minimo = PHP_INT_MAX; // El valor máximo que puede tener un entero en php
	  // Recorremos los dos equipos para calcular la suma de los puntos de sus fichas
	  foreach (array($equipo1, $equipo2) as $equipo) {
	    // Inicializamos la variable para almacenar la suma de los puntos del equipo
	    $suma = 0;
	    // Recorremos los jugadores del equipo
	    foreach ($equipo as $clave) {
	      // Recorremos las fichas del jugador
	      foreach ($this->jugadores[$clave] as $ficha) {
	        // Cada ficha es un array de dos elementos, que representan los puntos de cada lado
	        // Sumamos los dos elementos y los añadimos a la suma del equipo
	        $suma += $ficha[0] + $ficha[1];
	      }
	    }
	    // Comparamos la suma del equipo con el mínimo actual
	    if ($suma < $minimo) {
	      // Si la suma es menor que el mínimo, actualizamos el mínimo, el ganador y la puntuación
	      $minimo = $suma;
	      $ganador = $equipo;
	      $puntuacion = $suma;
	    }
	  }
	  // Devolvemos el equipo ganador y la puntuación como un array asociativo
	  return array("ganador" => $ganador, "puntuacion" => $puntuacion);
	}

}

$domino = new Domino();
$domino->Revolver();
$domino->Repartir();
//print_r( $domino->Busco_La_Cochina() );
$domino->Partida( 1 );
//print_r( $domino->mesa );
//echo "*************************\r\n";
//print_r( $domino->jugadores );

?></pre>

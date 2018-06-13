<?php
class ProceduresController extends AppController {

    public $uses = array('Producto');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function producto()
	{
		
		$data = $this->Producto->query(
				'CREATE DEFINER=`unocdcom`@`localhost` PROCEDURE unocdcom_pchat1.`sp_actualiza_pconsolidados`()
				      BEGIN 

                                          DELETE FROM unocdcom_pchat1.p_consolidados;
                                          INSERT INTO unocdcom_pchat1.p_consolidados
(
codi,
nombre,
ncli,
presentacion,
DISPLAYSporCAJA,
UNIDADESporDISPLAY,
peso,
volumen,
existencia,
ancho,
largo,
profundidad,
ExistenciaDisplays,
ExistenciaUnidades,
observaciones,
precio,
CajasPorPaletas,
CostoPrd,
clasprd1,
clasprd2,
clasprd3,
clasprd4,
clasprd5,
clasPrd1_descripcion,
clasPrd2_descripcion,
clasPrd3_descripcion,
clasPrd4_descripcion,
clasPrd5_descripcion,
fCreacion,
FecMod,
UFECHAE,
UFECHAS,
rotacion
)
SELECT 
	Producto.codi, 
	Producto.nombre,
	Producto.ncli  ,
	CASE WHEN Producto.presentacion = \' \' THEN \'n/a\' ELSE Producto.presentacion END AS presentacion ,
	CASE WHEN Producto.DISPLAYSporCAJA IS NULL THEN 0 ELSE Producto.DISPLAYSporCAJA END AS displaysporcaja, 
	CASE WHEN Producto.UNIDADESporDISPLAY IS NULL THEN 0 ELSE Producto.UNIDADESporDISPLAY END AS unidadespordisplay, 
	CASE WHEN Producto.PESO IS NULL THEN 0 ELSE Producto.PESO END AS peso , 
	CASE WHEN Producto.VOLUMEN IS NULL THEN 0 ELSE Producto.VOLUMEN END AS volumen , 
	CASE WHEN Producto.existencia IS NULL THEN 0 ELSE Producto.existencia END AS existencia ,
	CASE WHEN Producto.ancho IS NULL THEN 0 ELSE Producto.ancho END AS ancho, 
	CASE WHEN Producto.largo IS NULL THEN 0 ELSE Producto.largo END AS largo,
	CASE WHEN Producto.profundidad IS NULL THEN 0 ELSE Producto.profundidad END AS profundidad,
	CASE WHEN Producto.ExistenciaDisplays IS NULL THEN 0 ELSE Producto.ExistenciaDisplays END AS existenciadisplays ,
	CASE WHEN Producto.ExistenciaUnidades IS NULL THEN 0 ELSE Producto.ExistenciaUnidades END AS existenciaunidades ,
	CASE WHEN Producto.observaciones IS NULL THEN \'n/a\' ELSE Producto.observaciones END AS observaciones ,
	CASE WHEN Producto.Precio IS NULL THEN 0 ELSE Producto.PRECIO END AS precio ,
	CASE WHEN Producto.CajasPorPaletas IS NULL THEN 0 ELSE Producto.CajasPorPaletas END AS cajasporpaletas ,
	CASE WHEN Producto.CostoPrd IS NULL THEN 0 ELSE Producto.CostoPrd END AS costoprd,	
	Producto.clasprd1,	
	Producto.clasprd2,
	Producto.clasprd3,
	Producto.clasprd4,
	Producto.clasprd5,
	ClasPrd1.descripcion AS clasPrd1_descripcion,
	ClasPrd2.descripcion AS clasPrd2_descripcion,
	ClasPrd3.descripcion AS clasPrd3_descripcion,
	ClasPrd4.descripcion AS clasPrd4_descripcion,
	ClasPrd5.descripcion AS clasPrd5_descripcion,
	Producto.fCreacion ,
	Producto.FecMod ,
	Producto.UFECHAE,
	Producto.UFECHAS,
	CASE WHEN Producto.UFECHAS IS NOT NULL THEN  DATEDIFF(NOW(),Producto.UFECHAS)   ELSE 0 END AS \'rotacion\' 

FROM productos AS Producto 
	LEFT OUTER JOIN ClasPrd1 AS ClasPrd1 ON (Producto.ClasPrd1 = ClasPrd1.nclas) 
	LEFT OUTER JOIN ClasPrd2 AS ClasPrd2 ON (Producto.ClasPrd2 = ClasPrd2.nclas) 
	LEFT OUTER JOIN ClasPrd3 AS ClasPrd3 ON (Producto.ClasPrd3 = ClasPrd3.nclas) 
	LEFT OUTER JOIN ClasPrd4 AS ClasPrd4 ON (Producto.ClasPrd4 = ClasPrd4.nclas) 
	LEFT OUTER JOIN ClasPrd5 AS ClasPrd5 ON (Producto.ClasPrd5 = ClasPrd5.nclas)
ORDER BY Producto.ncli;
			              END ;		
				'
		);
		$this->autoRender = false;
		debug($data); 
	}
			
	public function existencia()
	{
		$data = $this->Producto->query('
					
						CREATE DEFINER=`unocdcom`@`localhost` PROCEDURE unocdcom_pchat1.`sp_actualiza_existencia`()
						BEGIN 
						
							 SET SQL_SAFE_UPDATES=0;
							
							-- CREO TABLA TEMPORALES  --
							
							CREATE TABLE  unocdcom_pchat1.existencia_temp
							  AS (SELECT ncli, MAX(fecha) AS fecha, codi, nestprd, unidades
							FROM unocdcom_pchat1.Existencia 
							GROUP BY  codi, nestprd
							);
							
							DROP TABLE IF EXISTS unocdcom_pchat1.existencia_historica;
							CREATE TABLE  unocdcom_pchat1.existencia_historica
							  AS (SELECT ncli, fecha, codi, nestprd, unidades
							FROM unocdcom_pchat1.Existencia 
							);
						
							-- VACIO LA TABLA EXISTENCIA Y LA CARGO EN BASE A LA TABLA TEMPORAL --
						
							DELETE FROM unocdcom_pchat1.Existencia;
							INSERT INTO unocdcom_pchat1.Existencia (ncli, fecha, codi, nestprd, unidades)
								SELECT TEMP.ncli, TEMP.fecha, TEMP.codi, TEMP.nestprd, TEMP.unidades
											FROM  unocdcom_pchat1.existencia_temp as TEMP;
						   
							-- ELIMINO LA TABLA TEMPORAL --
							DROP TABLE IF EXISTS unocdcom_pchat1.existencia_temp;
							
						 END ;		
		');
		$this->autoRender = false;
		debug($data);
	}

	public function clasprd1()
	{
		$data = $this->Producto->query('
						
						CREATE DEFINER=`unocdcom`@`localhost` PROCEDURE unocdcom_pchat1.`sp_actualiza_clasprd1`()
						BEGIN 
							SET SQL_SAFE_UPDATES=0;
							
							DELETE FROM `unocdcom_pchat1`.clasprd1_clientes;
							
							INSERT INTO `unocdcom_pchat1`.clasprd1_clientes
							(`cliente_id`, `clasprd_id`, `descripcion` )
							SELECT DISTINCT a.ncli, a.clasprd1, b.descripcion
							 FROM `unocdcom_pchat2`.productos as a
								INNER JOIN `unocdcom_pchat2`.ClasPrd1 as b ON (a.clasprd1 = b.nclas)
								INNER JOIN `unocdcom_pchat2`.Clientes as c ON (a.ncli = c.ncli)
							WHERE a.clasprd1 != \'\'
							ORDER BY a.ncli;
						 END;		
		');
		$this->autoRender = false;
		debug($data);
	}

	public function clasprd2()
	{
		$data = $this->Producto->query('
						
						CREATE DEFINER=`unocdcom`@`localhost` PROCEDURE unocdcom_pchat1.`sp_actualiza_clasprd2`()
						BEGIN 
							SET SQL_SAFE_UPDATES=0;
							
							DELETE FROM `unocdcom_pchat1`.clasprd2_clientes;
							
							INSERT INTO `unocdcom_pchat1`.clasprd2_clientes
							(`cliente_id`, `clasprd_id`, `descripcion` )
							SELECT DISTINCT a.ncli, a.clasprd2, b.descripcion
							 FROM `unocdcom_pchat1`.productos as a
								INNER JOIN `unocdcom_pchat1`.ClasPrd2 as b ON (a.clasprd2 = b.nclas)
								INNER JOIN `unocdcom_pchat1`.Clientes as c ON (a.ncli = c.ncli)
							WHERE a.clasprd2 != \'\'
							ORDER BY a.ncli;
						 END;			
		');
		$this->autoRender = false;
		debug($data);
	}

	public function clasprd3()
	{
		$data = $this->Producto->query('
						
						CREATE DEFINER=`unocdcom`@`localhost` PROCEDURE unocdcom_pchat1.`sp_actualiza_clasprd3`()
						BEGIN 
							SET SQL_SAFE_UPDATES=0;
							
							DELETE FROM `unocdcom_pchat1`.clasprd3_clientes;
							
							INSERT INTO `unocdcom_pchat1`.clasprd3_clientes
							(`cliente_id`, `clasprd_id`, `descripcion` )
							SELECT DISTINCT a.ncli, a.clasprd3, b.descripcion
							 FROM `unocdcom_pchat1`.productos as a
								INNER JOIN `unocdcom_pchat1`.ClasPrd3 as b ON (a.clasprd3 = b.nclas)
								INNER JOIN `unocdcom_pchat1`.Clientes as c ON (a.ncli = c.ncli)
							WHERE a.clasprd3 != \'\'
							ORDER BY a.ncli;
						 END;			
		');
		$this->autoRender = false;
		debug($data);
	}

	public function clasprd4()
	{
		$data = $this->Producto->query('
						
						CREATE DEFINER=`unocdcom`@`localhost` PROCEDURE unocdcom_pchat1.`sp_actualiza_clasprd4`()
						BEGIN 
							SET SQL_SAFE_UPDATES=0;
							
							DELETE FROM `unocdcom_pchat1`.clasprd4_clientes;
							
							INSERT INTO `unocdcom_pchat1`.clasprd4_clientes
							(`cliente_id`, `clasprd_id`, `descripcion` )
							SELECT DISTINCT a.ncli, a.clasprd4, b.descripcion
							 FROM `unocdcom_pchat1`.Productos as a
								INNER JOIN `unocdcom_pchat1`.ClasPrd4 as b ON (a.clasprd4 = b.nclas)
								INNER JOIN `unocdcom_pchat1`.Clientes as c ON (a.ncli = c.ncli)
							WHERE a.clasprd4 != \'\'
							ORDER BY a.ncli;
						 END;		
		');
		$this->autoRender = false;
		debug($data);
	}

	public function clasprd5()
	{
		$data = $this->Producto->query('
						
						CREATE DEFINER=`unocdcom`@`localhost` PROCEDURE unocdcom_pchat1.`sp_actualiza_clasprd5`()
						BEGIN 
							SET SQL_SAFE_UPDATES=0;
							
							DELETE FROM `unocdcom_pchat1`.clasprd5_clientes;
							
							INSERT INTO `unocdcom_pchat1`.clasprd5_clientes
							(`cliente_id`, `clasprd_id`, `descripcion` )
							SELECT DISTINCT a.ncli, a.clasprd5, b.descripcion
							 FROM `unocdcom_pchat1`.productos as a
								INNER JOIN `unocdcom_pchat1`.ClasPrd5 as b ON (a.clasprd5 = b.nclas)
								INNER JOIN `unocdcom_pchat2`.Clientes as c ON (a.ncli = c.ncli)
							WHERE a.clasprd5 != \'\'
							ORDER BY a.ncli;
						 END;		
		');
		$this->autoRender = false;
		debug($data);
	}
}
?>
-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-07-2022 a las 23:35:43
-- Versión del servidor: 8.0.29-cll-lve
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jasschos_jass`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_actualiza_rbos_vencidos` (OUT `rpta` INT)  BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE codigo INT;

    DECLARE recibos CURSOR FOR
        SELECT RBO_CODIGO
        FROM RECIBO 
        WHERE RBO_ESTADO = 1 AND
        CURDATE() > RBO_ULT_DIA_PAGO;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN recibos;
    loop_recibos: LOOP
        -- Fetch lo utilizamos para leer y almacenar cada uno de los recibos
        FETCH recibos INTO codigo;
        -- If que permite salir del ciclo
        IF done THEN 
            LEAVE loop_recibos; 
        END IF;

        UPDATE RECIBO SET RBO_ESTADO = 3 WHERE RBO_CODIGO = codigo;
    END LOOP;

    SET rpta = 200;
    CLOSE recibos;

END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_agregar_Usuario` (IN `_Nombres` VARCHAR(50), IN `_Apellidos` VARCHAR(50), IN `_Usuario` VARCHAR(50), IN `_Email` VARCHAR(50), IN `_Password` VARCHAR(260), IN `_TipoUsu` INT(11))  BEGIN
    START TRANSACTION;
 	INSERT INTO USUARIO(USU_NOMBRES,
                        USU_APELLIDOS,
                        USU_USUARIO,
                        USU_EMAIL,
                        USU_PASSWORD,
                        USU_ESTADO,
                        USU_CREATED,
                        USU_UPDATED,
                        TPU_CODIGO) VALUES
 	(_Nombres, _Apellidos, _Usuario, _Email, _Password, 1,CURDATE(),CURDATE(), _TipoUsu);
    COMMIT;
 END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_calcula_montos_rbos` (IN `_fecha` DATE)  BEGIN
    DECLARE total double(10,2) DEFAULT 0;
    DECLARE done INT DEFAULT FALSE;
    DECLARE codigo double(10,2) DEFAULT 0;
    DECLARE monto1 double(10,2) DEFAULT 0;
    DECLARE monto2 double(10,2) DEFAULT 0;
    DECLARE monto3 double(10,2) DEFAULT 0;
    DECLARE igv double(10,2) DEFAULT 0;
    DECLARE v_igv INT DEFAULT 0;

    DECLARE registros CURSOR FOR
        SELECT RBO_CODIGO, RBO_MNTO_CONSUMO, RBO_MNTO_SERV_ADI, RBO_MNTO_FIN_CUOTA 
        FROM RECIBO 
        WHERE RBO_FEC_PERIODO = _fecha;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SET v_igv = (SELECT IGV_PORCENTAJE FROM IGV);

    OPEN registros;
    loop_registros: LOOP
        -- Fetch lo utilizamos para leer y almacenar cada uno de los registros
        FETCH registros INTO codigo, monto1, monto2, monto3;
        -- If que permite salir del ciclo
        IF done THEN 
            LEAVE loop_registros; 
        END IF;
        -- CALCULO EL IGV
        SET igv = ((monto1 * v_igv) / 100);

        SET total = ((monto1 + monto2 + monto3) + igv);

        UPDATE RECIBO SET RBO_MNTO_TOTAL = total, RBO_IGV = v_igv WHERE RBO_CODIGO = codigo AND RBO_FEC_PERIODO = _fecha;

        SET total = 0;
    END LOOP;

    CLOSE registros;
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_datos_financiamiento` (IN `_codigo` INT)  BEGIN
    SELECT DATE_FORMAT(FTO_CREATED,'%Y-%m-%d') AS FECHA,
            F.FTO_CUOTA_INICIAL AS INICIAL,
            F.FTO_NUM_CUOTAS AS CUOTA,
            F.FTO_MONTO_CUOTA AS MONTO_CUOTA 
            FROM FINANCIAMIENTO AS F
            WHERE F.FTO_CODIGO = _codigo;
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_datos_usuario_financiado` (IN `_codigo` INT)  BEGIN
    DECLARE cod_contrato INT;

    SET cod_contrato = (SELECT CTO_CODIGO FROM FINANCIAMIENTO WHERE FTO_CODIGO = _codigo);

    SELECT C.CLI_NOMBRES AS USUARIO, C.CLI_DOCUMENTO AS DOCUMENTO,
        P.PRE_DIRECCION AS DIRECCION, 
        (SELECT fc_get_importe_contrato(cod_contrato)) AS IMPORTE_CONSUMO,
        (SELECT FTO_DEUDA AS DEUDA FROM FINANCIAMIENTO WHERE FTO_CODIGO = _codigo) AS DEUDA
        FROM CONTRATO AS CT
        INNER JOIN PREDIO AS P ON CT.PRE_CODIGO = P.PRE_CODIGO
        INNER JOIN CLIENTE AS C ON P.CLI_CODIGO = C.CLI_CODIGO
        WHERE CT.CTO_CODIGO = cod_contrato;
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_generar_recibos_mensual` (IN `_fecFacturacion` DATE, OUT `code_rpta` INT)  sp_label: BEGIN
    -- Declaro variables 
    DECLARE done INT DEFAULT FALSE;
    DECLARE cod_cont INT;
    DECLARE cod_rbo INT;
    DECLARE v_periodos INT DEFAULT 0;
    DECLARE v_rbo_pendientes INT DEFAULT 0;
    DECLARE m_importe double(10,2) DEFAULT 0;
    DECLARE m_serv_adi double(10,2) DEFAULT 0;
    DECLARE m_finac_cuota double(10,2) DEFAULT 0;

    DECLARE v_fecUltPago DATE;
    DECLARE v_fecCorte DATE;
    DECLARE v_new_periodo varchar(20);

    DECLARE v_codigoCuota INT;
    DECLARE v_monto double(10,2);


    DECLARE igv INT DEFAULT 0;


    -- Declaro CURSOR para leer tabla obtenida de la consulta
    DECLARE contratos CURSOR FOR
        SELECT CTO_CODIGO
        FROM CONTRATO 
        WHERE CTO_ESTADO = 1 OR CTO_ESTADO = 4
        ORDER BY CTO_CODIGO;

    -- Manejador para excepciones que pueda ocurra durante el proceso
    -- 
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
      BEGIN 
        SET code_rpta = 500;
        ROLLBACK;
    END;

    -- Manejador de eventos para saber cuando tiene que detenerse
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    -- DECLARE CONTINUE HANDLER FOR 2014 SET done2 = TRUE;
    
    -- Abrir CURSOR
    OPEN contratos;
    
    SET v_new_periodo = (SELECT fc_formato_mes( _fecFacturacion));
    SET v_fecUltPago = DATE_ADD(CURDATE(), INTERVAL 10 DAY);
    SET v_fecCorte = DATE_ADD(v_fecUltPago, INTERVAL 1 DAY);
    SET v_periodos = (SELECT COUNT(*) FROM RECIBO WHERE RBO_PERIODO = v_new_periodo);
    SET igv = (SELECT IGV_PORCENTAJE FROM IGV);

    IF (v_periodos > 0) THEN
        SET code_rpta = 400;
    ELSE
        START TRANSACTION;
        loop_arreglo: LOOP
            -- Fetch lo utilizamos para leer y almacenar cada uno de los registros
            FETCH contratos INTO cod_cont;
            -- If que permite salir del ciclo
            IF done THEN 
                LEAVE loop_arreglo; 
            END IF;

            SET v_rbo_pendientes = (SELECT COUNT(RBO_ESTADO) FROM RECIBO WHERE RBO_ESTADO = 1 AND CTO_CODIGO = cod_cont);


            SET cod_rbo = (SELECT RBO_CODIGO FROM RECIBO ORDER BY RBO_CODIGO DESC LIMIT 1);

            -- SI EL VALOR ES NULL TOMA 0
            SET cod_rbo = IFNULL(cod_rbo, 0);

            SET cod_rbo = cod_rbo + 1;
            -- registro datos en la tabla recibo
            INSERT INTO RECIBO(RBO_PERIODO,
                                RBO_FEC_PERIODO,
                                RBO_ULT_DIA_PAGO, 
                                RBO_FECHA_CORTE, 
                                RBO_MNTO_CONSUMO,
                                RBO_MNTO_SERV_ADI,
                                RBO_IGV,
                                RBO_ESTADO, 
                                RBO_CREATED, 
                                RBO_UPDATED, 
                                CTO_CODIGO) VALUES
                        ( v_new_periodo, _fecFacturacion, v_fecUltPago, 
                        IF(v_rbo_pendientes > 0, v_fecCorte, NULL), 
                        (SELECT fc_get_importe_contrato(cod_cont)),
                        (SELECT fc_get_serv_adicional_rbo(cod_cont, cod_rbo)),
                        igv, 1, CURDATE(),  CURDATE(), cod_cont);

            
            -- ACTUALIZANDO LA TABLA RECIBO Y FINANCIAMIENTO MNTO_FIC
            SET v_codigoCuota = (SELECT fc_get_codigo_cuota_financia(cod_cont, cod_rbo));
            SET v_monto = (SELECT fc_get_monto_financia(cod_cont, cod_rbo));


            IF ( v_codigoCuota <> NULL ) THEN
                UPDATE FINANC_CUOTA SET FCU_ESTADO = 2 WHERE FCU_CODIGO = v_codigoCuota;
                UPDATE RECIBO SET FCU_CODIGO = v_codigoCuota, RBO_MNTO_FIN_CUOTA = v_monto WHERE RBO_CODIGO = cod_rbo;
            END IF;
  
        END LOOP;
        COMMIT;

        CALL sp_calcula_montos_rbos(_fecFacturacion);
        SET code_rpta =  200;
    
    END IF;
     
    -- CIERRO CURSOR
    CLOSE contratos;
 END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_cuotas_finan` (IN `_codFinanciamiento` INT)  BEGIN
    
    SELECT FCU_NUM_CUOTA AS CUOTA, FCU_FECHA_DE_CRONOGRAMA AS CRONOGRAMA,
    FCU_MONTO_CUOTA AS MONTO,
    (SELECT RBO_MNTO_CONSUMO FROM RECIBO WHERE FTO_CODIGO = _codFinanciamiento LIMIT 1) AS CUOTA_MES
    FROM FINANC_CUOTA 
    WHERE FTO_CODIGO = _codFinanciamiento
    AND FCU_ESTADO = 1;

END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_datos_rbo` (IN `_codigo` INT)  BEGIN 
    SELECT R.RBO_CODIGO AS CODIGO, R.RBO_PERIODO AS PERIODO, 
              DATE_FORMAT(R.RBO_CREATED, '%d/%m/%Y') AS 'FEC_EMISION', 
              DATE_FORMAT(R.RBO_ULT_DIA_PAGO, '%d/%m/%Y') AS 'ULT_DIA_PAGO',
              DATE_FORMAT(R.RBO_FECHA_CORTE, '%d/%m/%Y') AS 'FECHA_CORTE',
              R.RBO_MNTO_TOTAL AS 'MONTO_TOTAL',
              R.CTO_CODIGO AS CONTRATO,
              CT.CLI_NOMBRES AS CLIENTE,
              P.PRE_DIRECCION AS DIRECCION,
              R.RBO_IGV AS IGV,
              R.RBO_MNTO_CONSUMO AS MNTO_CONSUMO
    FROM RECIBO AS R
    INNER JOIN CONTRATO AS C ON R.CTO_CODIGO =  C.CTO_CODIGO
    INNER JOIN PREDIO AS P ON C.PRE_CODIGO = P.PRE_CODIGO
    INNER JOIN CLIENTE AS CT ON P.CLI_CODIGO = CT.CLI_CODIGO
    WHERE R.RBO_CODIGO = _codigo;
    
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_datos_ticket` (IN `_cod` INT, IN `_tipo` VARCHAR(5))  BEGIN
        IF (_tipo = 'RBO') THEN
            SELECT ANY_VALUE(I.IGR_COD_COMPROBANTE) AS CODIGO, 
            ANY_VALUE(I.IGR_CANTIDAD) AS CANTIDAD,
            ANY_VALUE(I.IGR_IGV) AS IGV, 
            ANY_VALUE(I.IGR_MNTO_RECIBIDO) AS MNTO_RECIBIDO,
            IF(ANY_VALUE(I.IGR_TIPO_COMPROBANTE) = 1,'NORMAL', IF(ANY_VALUE(I.IGR_TIPO_COMPROBANTE) = 2,'BOLETA','FACTURA'))AS TIPO_PAGO,
            ANY_VALUE(I.IGR_CREATED) AS FEC_EMISION,
            ANY_VALUE(R.RBO_PERIODO) AS REF,
            ANY_VALUE(CT.CTO_CODIGO) AS COD_CLIENTE,
            ANY_VALUE(C.CLI_NOMBRES) AS NOMBRES,
            ANY_VALUE(P.PRE_DIRECCION) AS DIRECCION,
            IF(ANY_VALUE(CT.CTO_ESTADO) = 4, 'SERVICIO EN MANTENIMIENTO', IF(COUNT(S.SRV_CODIGO) = 2,'AGUA POTABLE Y ALCANTARILLADO', ANY_VALUE(S.SRV_NOMBRE))) AS SERVICIO,
            (SELECT CONCAT(USU_CODIGO,' - ',USU_USUARIO) FROM USUARIO WHERE USU_CODIGO = ANY_VALUE(I.USU_CODIGO)) AS CAJERO
            FROM INGRESO AS I
            INNER JOIN RECIBO AS R ON R.IGR_CODIGO =  I.IGR_CODIGO
            INNER JOIN CONTRATO AS CT ON R.CTO_CODIGO =  CT.CTO_CODIGO
            INNER JOIN PREDIO AS P ON CT.PRE_CODIGO =  P.PRE_CODIGO
            INNER JOIN CLIENTE AS C ON P.CLI_CODIGO =  C.CLI_CODIGO
            INNER JOIN SERVICIO_CONTRATO AS SC ON SC.CTO_CODIGO =  CT.CTO_CODIGO
            INNER JOIN SERVICIO AS S ON SC.SRV_CODIGO =  S.SRV_CODIGO
            WHERE I.IGR_CODIGO = _cod;

        ELSEIF (_tipo = 'CUE') THEN
            SELECT I.IGR_COD_COMPROBANTE AS CODIGO, I.IGR_CANTIDAD AS CANTIDAD,
            I.IGR_CREATED AS FEC_EMISION,
            I.IGR_MNTO_RECIBIDO AS MNTO_RECIBIDO,
            IF(I.IGR_TIPO_COMPROBANTE = 1,'NORMAL',IF(I.IGR_TIPO_COMPROBANTE = 2,'BOLETA','FACTURA')) AS TIPO_PAGO,
            CE.CUE_NUM_CUOTA AS REF,
            CT.CTO_CODIGO AS COD_CONTRATO,
            C.CLI_NOMBRES AS NOMBRES,
            P.PTO_NOMBRE AS PROYECTO,
            (SELECT CONCAT(USU_CODIGO,' - ',USU_USUARIO) FROM USUARIO WHERE USU_CODIGO = I.USU_CODIGO) AS CAJERO
            FROM INGRESO AS I
            INNER JOIN CUOTA_EXTRAORDINARIA AS CE ON CE.IGR_CODIGO =  I.IGR_CODIGO
            INNER JOIN PROYECTO AS P ON CE.PTO_CODIGO =  P.PTO_CODIGO
            INNER JOIN CONTRATO AS CT ON CE.CTO_CODIGO =  CT.CTO_CODIGO
            INNER JOIN PREDIO AS PR ON CT.PRE_CODIGO =  PR.PRE_CODIGO
            INNER JOIN CLIENTE AS C ON PR.CLI_CODIGO =  C.CLI_CODIGO
            WHERE I.IGR_CODIGO = _cod;

        ELSE
            SELECT I.IGR_COD_COMPROBANTE AS CODIGO, I.IGR_CANTIDAD AS CANTIDAD,
            I.IGR_MNTO_RECIBIDO AS MNTO_RECIBIDO,
            I.IGR_DESCRIPCION AS CONCEPTO,
            IF(I.IGR_TIPO_COMPROBANTE = 1,'NORMAL',IF(I.IGR_TIPO_COMPROBANTE = 2,'BOLETA','FACTURA')) AS TIPO_PAGO,
            I.IGR_CREATED AS FEC_EMISION,
            (SELECT CONCAT(USU_CODIGO,' - ',USU_USUARIO) FROM USUARIO WHERE USU_CODIGO = I.USU_CODIGO) AS CAJERO
            FROM INGRESO AS I
            WHERE I.IGR_CODIGO = _cod;
        END IF;
    
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_detalles_rbo` (IN `_cod` INT)  BEGIN 
        -- SENTENCIA PARA RECORRER SUS SERVICIO CONTRATADO
        SELECT IF(ANY_VALUE(C.CTO_ESTADO) = 4, 'SERVICIO EN MANTENIMIENTO', IF(COUNT(S.SRV_CODIGO) = 2,'AGUA POTABLE Y ALCANTARILLADO', ANY_VALUE(S.SRV_NOMBRE))) AS DESCRIPCION,
			    IF(ANY_VALUE(C.CTO_ESTADO) = 4, ANY_VALUE(TU.TUP_TARIFA_MANTENIMIENTO), IF(COUNT(S.SRV_CODIGO) = 2, ANY_VALUE(TU.TUP_TARIFA_AMBOS), IF(ANY_VALUE(S.SRV_CODIGO) = 1, ANY_VALUE(TU.TUP_TARIFA_AGUA), ANY_VALUE(TU.TUP_TARIFA_DESAGUE)))) AS MONTO,
                CONCAT('0') AS AGREGAR_MONTO
        FROM SERVICIO_CONTRATO AS SC 
        INNER JOIN SERVICIO AS S ON SC.SRV_CODIGO = S.SRV_CODIGO
        INNER JOIN CONTRATO AS C ON SC.CTO_CODIGO = C.CTO_CODIGO
        INNER JOIN TIPO_USO_PREDIO AS TU ON C.TUP_CODIGO = TU.TUP_CODIGO
        WHERE C.CTO_CODIGO = fc_get_cod_cliente(_cod)
        

        UNION
        -- SENTENCIA PARA RECORRER SUS SERVICIOS ADICIONALES
        SELECT SAR_DESCRIPCION AS DESCRIPCION, SAR_COSTO  AS MONTO, CONCAT('0') AS AGREGAR_MONTO
        FROM SERVICIO_ADICIONAL_RBO 
        WHERE SAR_CODIGO_RBO = _cod AND SAR_ESTADO = 2

        UNION
        -- SENTENCIA PARA MOSTRAR CUOTAS REFINANCIADAS EN RECIBO
        SELECT CONCAT('CUOTA FINANCIADA Nro. ', FCU_CODIGO ) AS DESCRIPCION, RBO_MNTO_FIN_CUOTA AS MONTO,
        CONCAT('0') AS AGREGAR_MONTO 
        FROM RECIBO 
        WHERE RBO_CODIGO = _cod AND FCU_CODIGO <> NULL

        UNION
        -- SENTENCIA PARA RECORRER SUS RECIBOS PENDIENTES
        SELECT CONCAT('RECIBO PENDIENTE DE PAGO MES ', RBO_PERIODO,' REF. ', RBO_CODIGO ) AS DESCRIPCION, RBO_MNTO_TOTAL AS MONTO,
        CONCAT('1') AS AGREGAR_MONTO 
        FROM RECIBO 
        WHERE CTO_CODIGO = fc_get_cod_cliente(_cod) AND RBO_ESTADO = 3
        AND RBO_CODIGO <> _cod;

END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_egresos` (IN `_fechaSel` DATE)  BEGIN
    SELECT E.EGR_CREATED AS FECHA, 
            E.EGR_DESCRIPCION AS DETALLE, 
            E.EGR_COD_COMPROBANTE AS RBO_NUMERO,
            E.EGR_TIPO_COMPROBANTE AS TIPO_COMP, 
            E.EGR_CANTIDAD AS TOTAL,
            IF(E.EGR_ESTADO = 1,'','ANULADO') AS ESTADO,
            (SELECT CAJ_NOMBRE FROM CAJA WHERE CAJ_CODIGO = E.CAJ_CODIGO ) AS CAJA
    FROM EGRESO AS E
    WHERE DATE(E.EGR_CREATED) = _fechaSel
    ORDER BY FECHA;
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_ingresos` (IN `_fechaSel` DATE)  BEGIN

    SELECT I.IGR_CREATED AS FECHA, 
            C.CLI_NOMBRES AS NOMBRES, 
            I.IGR_COD_COMPROBANTE AS RBO_NUMERO,
            I.IGR_DESCRIPCION AS DETALLE, 
            I.IGR_IGV AS IGV, 
            I.IGR_CANTIDAD AS TOTAL,
            IF(I.IGR_ESTADO = 1,'','ANULADO') AS ESTADO, 
            I.CAJ_CODIGO AS CAJA
    FROM INGRESO AS I
    INNER JOIN RECIBO AS R ON R.IGR_CODIGO =  I.IGR_CODIGO
    INNER JOIN CONTRATO AS CT ON R.CTO_CODIGO =  CT.CTO_CODIGO
    INNER JOIN PREDIO AS P ON CT.PRE_CODIGO =  P.PRE_CODIGO
    INNER JOIN CLIENTE AS C ON P.CLI_CODIGO =  C.CLI_CODIGO
    WHERE (I.CAJ_CODIGO = 1 OR I.CAJ_CODIGO = 2)
    AND DATE(I.IGR_CREATED) = _fechaSel

    UNION 

    SELECT I.IGR_CREATED AS FECHA, 
            C.CLI_NOMBRES AS NOMBRES,
            I.IGR_COD_COMPROBANTE AS RBO_NUMERO,
            I.IGR_DESCRIPCION AS DETALLE, 
            I.IGR_IGV AS IGV, 
            I.IGR_CANTIDAD AS TOTAL,
            IF(I.IGR_ESTADO = 1,'','ANULADO') AS ESTADO, 
            I.CAJ_CODIGO AS CAJA
    FROM INGRESO AS I
    INNER JOIN CUOTA_EXTRAORDINARIA AS CE ON CE.IGR_CODIGO =  I.IGR_CODIGO
    INNER JOIN PROYECTO AS PT ON CE.PTO_CODIGO =  PT.PTO_CODIGO
    INNER JOIN CONTRATO AS CT ON CE.CTO_CODIGO =  CT.CTO_CODIGO
    INNER JOIN PREDIO AS P ON CT.PRE_CODIGO =  P.PRE_CODIGO
    INNER JOIN CLIENTE AS C ON P.CLI_CODIGO =  C.CLI_CODIGO
    WHERE (I.CAJ_CODIGO = 1 OR I.CAJ_CODIGO = 2)
    AND DATE(I.IGR_CREATED) = _fechaSel

    UNION

    SELECT IGR_CREATED AS FECHA, 
            CONCAT(' ') AS NOMBRES,
            IGR_COD_COMPROBANTE AS RBO_NUMERO,
            IGR_DESCRIPCION AS DETALLE,
            IGR_IGV AS IGV,
            IGR_CANTIDAD AS TOTAL,
            IF(IGR_ESTADO = 1,'','ANULADO') AS ESTADO, 
            CAJ_CODIGO AS CAJA
    FROM INGRESO 
    WHERE (IGR_TIPO = 'OTRO' OR  IGR_TIPO = 'TRANSF')
    AND (CAJ_CODIGO = 1 OR CAJ_CODIGO = 2)
    AND DATE(IGR_CREATED) = _fechaSel
    
    ORDER BY FECHA;

END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_otros_rbo` (IN `_cod` INT)  BEGIN 
        DECLARE cod_cont INT;
        SET cod_cont = (SELECT CTO_CODIGO FROM RECIBO WHERE RBO_CODIGO = _cod);

        SELECT CONCAT('PROYECTO ', PT.PTO_NOMBRE, ' : CUOTA NRO. ', CE.CUE_NUM_CUOTA, ' S/. ', CE.CUE_MNTO_CUOTA ) AS OTROS
        FROM CONTRATO AS C
        INNER JOIN CUOTA_EXTRAORDINARIA AS CE ON CE.CTO_CODIGO =  C.CTO_CODIGO
        INNER JOIN PROYECTO AS PT ON CE.PTO_CODIGO =  PT.PTO_CODIGO
        WHERE C.CTO_CODIGO = cod_cont AND CE.CUE_ESTADO = 1
        LIMIT 1;
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_rbos_contrato` (IN `_cod` INT)  BEGIN 

    SELECT DATE_FORMAT(R.RBO_FEC_PERIODO, '%Y-%m')  AS PERIODO, R.RBO_CODIGO AS REF, DATE_FORMAT(R.RBO_CREATED, '%Y-%m-%d') AS FECHA_EMISION, 
            R.RBO_MNTO_TOTAL AS IMPORTE_MES, R.RBO_ULT_DIA_PAGO AS FECHA_VENCE, 
            IF(R.IGR_CODIGO = NULL,'1900-01-01','1900-01-01') AS FECHA_PAGO, IF(R.RBO_ESTADO = 1,'PENDIENTE', IF(R.RBO_ESTADO = 3,'VENCIDO','FINANCIADO')) AS ESTADO
            FROM RECIBO AS R WHERE R.CTO_CODIGO = _cod AND R.RBO_ESTADO = 1 OR R.RBO_ESTADO = 3
                
            UNION ALL

    SELECT DATE_FORMAT(R.RBO_FEC_PERIODO, '%Y-%m') AS PERIODO, R.RBO_CODIGO AS REF, DATE_FORMAT(R.RBO_CREATED, '%Y-%m-%d')  AS FECHA_EMISION, 
            R.RBO_MNTO_TOTAL AS IMPORTE_MES, R.RBO_ULT_DIA_PAGO AS FECHA_VENCE, DATE_FORMAT(I.IGR_CREATED, '%Y-%m-%d')  AS FECHA_PAGO, 
            IF(R.RBO_ESTADO = 2,'PAGADO','PAGADO' ) AS ESTADO
            FROM RECIBO AS R 
            INNER JOIN INGRESO AS I ON R.IGR_CODIGO = I.IGR_CODIGO
            WHERE R.CTO_CODIGO = _cod
    
     ORDER BY PERIODO DESC LIMIT 12;
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_rbos_finan` (IN `_codFinanciamiento` INT)  BEGIN
    
    SELECT RBO_PERIODO AS RBO_MES, 
    RBO_MNTO_CONSUMO AS TARIFA
    FROM RECIBO 
    WHERE FTO_CODIGO = _codFinanciamiento;

END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_get_ticket_egreso` (IN `_cod` INT)  BEGIN
    SELECT  E.EGR_CODIGO AS CODIGO,
            E.EGR_CANTIDAD AS CANTIDAD, 
            E.EGR_COD_COMPROBANTE AS COMPROBANTE,
            E.EGR_DESCRIPCION AS CONCEPTO,
            IF(E.EGR_TIPO_COMPROBANTE = 1,'TICKET',IF(E.EGR_TIPO_COMPROBANTE = 2,'BOLETA','FACTURA')) AS TIPO_EMISION,
            E.EGR_CREATED AS FEC_EMISION,
            (SELECT CONCAT(USU_NOMBRES,' ',USU_APELLIDOS) FROM USUARIO WHERE USU_CODIGO = E.USU_CODIGO) AS CAJERO
            FROM EGRESO AS E
            WHERE E.EGR_CODIGO = _cod;
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_montos_arqueo_semanal` (IN `_fechaInicio` DATE, IN `_fechaFin` DATE)  BEGIN

    DECLARE saldoAct double;
    DECLARE ingresosHastaFA double;
    DECLARE egresosHastaFA double;

    DECLARE saldoIni double;
    DECLARE ingresosRangoFecha double;
    DECLARE egresosRangoFecha double;
    DECLARE saldoNetoRangoFecha double;
    
    SET saldoAct = (SELECT SUM(CAJ_SALDO) FROM CAJA);
    SET ingresosHastaFA = (SELECT SUM(IGR_CANTIDAD) AS TOTAL
                            FROM INGRESO 
                            WHERE IGR_ESTADO = 1 
                            AND DATE(IGR_CREATED) BETWEEN _fechaInicio AND CURDATE());

    SET egresosHastaFA = (SELECT SUM(EGR_CANTIDAD) AS TOTAL
                            FROM EGRESO 
                            WHERE EGR_ESTADO = 1 
                            AND DATE(EGR_CREATED) BETWEEN _fechaInicio AND CURDATE());

    SET saldoIni = (saldoAct - ingresosHastaFA + egresosHastaFA);

    SET ingresosRangoFecha = (SELECT SUM(IGR_CANTIDAD) AS TOTAL
                            FROM INGRESO 
                            WHERE IGR_ESTADO = 1 
                            AND DATE(IGR_CREATED) BETWEEN _fechaInicio AND _fechaFin);

    SET egresosRangoFecha = (SELECT SUM(EGR_CANTIDAD) AS TOTAL
                            FROM EGRESO 
                            WHERE EGR_ESTADO = 1 
                            AND DATE(EGR_CREATED) BETWEEN _fechaInicio AND _fechaFin);

    SET saldoNetoRangoFecha =  (ingresosRangoFecha - egresosRangoFecha);  

    SELECT  saldoIni AS 'SALDO_INICIAL',  ingresosRangoFecha AS 'INGRESOS', egresosRangoFecha AS 'EGRESOS';

END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_registros_reporte_semanal` (IN `_fechaInicio` DATE, IN `_fechaFin` DATE)  BEGIN
    -- INGRESOS
    SELECT DATE_FORMAT(DATE(IGR_CREATED),'%d-%m-%Y') AS DIA, 
            SUM(IGR_CANTIDAD) AS TOTAL,
            'I' AS TIPO
    FROM INGRESO 
    WHERE IGR_ESTADO = 1 
    AND DATE(IGR_CREATED) BETWEEN _fechaInicio AND _fechaFin
    GROUP BY IGR_CREATED

    UNION

    -- EGRESOS
    SELECT DATE_FORMAT(DATE(EGR_CREATED),'%d-%m-%Y') AS DIA , 
            SUM(EGR_CANTIDAD) AS TOTAL,
            'E' AS TIPO
    FROM EGRESO 
    WHERE EGR_ESTADO = 1 
    AND DATE(EGR_CREATED) BETWEEN _fechaInicio AND _fechaFin 
    GROUP BY EGR_CREATED;

END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_set_financiar_cuotas` (IN `_codFinanciado` INT, OUT `_msj` INT(3))  BEGIN
    
    DECLARE num_cuota INT DEFAULT 0;
    DECLARE monto_mes double(10,2);

    DECLARE fecha_inicial DATE;
    DECLARE fechas DATE; 
    DECLARE contador INT;

    -- SET deuda = (SELECT FTO_DEUDA FROM FINANCIAMIENTO  WHERE FTO_CODIGO = _codFinanciado);
    SET num_cuota = (SELECT FTO_NUM_CUOTAS FROM FINANCIAMIENTO  WHERE FTO_CODIGO = _codFinanciado);
    -- SET cuota_inicial = (SELECT FTO_CUOTA_INICIAL FROM FINANCIAMIENTO  WHERE FTO_CODIGO = _codFinanciado);
    SET monto_mes = (SELECT FTO_MONTO_CUOTA FROM FINANCIAMIENTO  WHERE FTO_CODIGO = _codFinanciado);
    SET fecha_inicial = (SELECT DATE_FORMAT(FTO_CREATED,'%Y-%m-%d') AS FECHA FROM FINANCIAMIENTO  WHERE FTO_CODIGO = _codFinanciado);

    IF num_cuota < 1 THEN
        SET _msj = 400;
    ELSE
        SET contador = 1;
        SET fechas = fecha_inicial;
        START TRANSACTION;
        WHILE contador <= num_cuota DO
            SET fechas = DATE_ADD(fechas, INTERVAL 1 MONTH);
            
            INSERT INTO FINANC_CUOTA (FCU_NUM_CUOTA, FCU_MONTO_CUOTA, FCU_ESTADO, 
                                        FCU_FECHA_DE_CRONOGRAMA, FTO_CODIGO,
                                        FCU_CREATED, FCU_UPDATED) VALUES
                                        (contador, monto_mes, 1, fechas, _codFinanciado,
                                        fechas, fechas);

            SET contador = contador + 1;
        END WHILE;
        COMMIT;
        SET _msj = 200;
    END IF;
  
END$$

CREATE DEFINER=`jasschos`@`localhost` PROCEDURE `sp_set_proyecto_cuotas` (IN `_codProyecto` INT, OUT `_msj` INT(3))  BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE num_cuota INT DEFAULT 0;
    DECLARE monto_cuota double(10,2);
    DECLARE montos double(10,2); 
    DECLARE contador INT;
    DECLARE cod_cto INT;
    
    DECLARE contratos CURSOR FOR 
        SELECT CTO_CODIGO FROM CONTRATO 
        WHERE CTO_ESTADO = 1 OR CTO_ESTADO = 4;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SET monto_cuota = (SELECT PTO_MNTO_CTO FROM PROYECTO WHERE PTO_CODIGO = _codProyecto);
    SET num_cuota = (SELECT PTO_NUM_CUOTAS FROM PROYECTO WHERE PTO_CODIGO = _codProyecto);
    
    SET montos = (monto_cuota / num_cuota);

    IF num_cuota < 1 THEN
        SET _msj = 500;
    ELSE
        OPEN contratos;
        START TRANSACTION;

        loop_contratos: LOOP
            -- Fetch lo utilizamos para leer y almacenar cada uno de los recibos
            FETCH contratos INTO cod_cto;
            -- If que permite salir del ciclo
            IF done THEN 
                LEAVE loop_contratos; 
            END IF;

            SET contador = 1;
            WHILE contador <= num_cuota DO
                INSERT INTO CUOTA_EXTRAORDINARIA (CUE_NUM_CUOTA, CUE_MNTO_CUOTA, CUE_ESTADO, 
                                        PTO_CODIGO,
                                        CTO_CODIGO,
                                        CUE_CREATED,
                                        CUE_UPDATED) VALUES
                                        (contador, montos, 1, 
                                        _codProyecto, cod_cto,
                                        CURDATE(),
                                        CURDATE());

                SET contador = contador + 1;
            END WHILE;
        END LOOP;

        COMMIT;
        CLOSE contratos;
        SET _msj = 200;
    END IF;
  
END$$

--
-- Funciones
--
CREATE DEFINER=`jasschos`@`localhost` FUNCTION `fc_formato_mes` (`_fecha` DATE) RETURNS VARCHAR(20) CHARSET utf8mb4 BEGIN
    DECLARE _mes INT;
    DECLARE _year INT;
    DECLARE _nombre VARCHAR(20);
    DECLARE formato VARCHAR(20);

    SET _mes = MONTH(_fecha);
    SET _year = YEAR(_fecha);

    CASE _mes
        WHEN 1 THEN SET _nombre = 'ENERO';
        WHEN 2  THEN SET _nombre = 'FEBRERO';
        WHEN 3  THEN SET _nombre = 'MARZO';
        WHEN 4  THEN SET _nombre = 'ABRIL';
        WHEN 5  THEN SET _nombre = 'MAYO';
        WHEN 6  THEN SET _nombre = 'JUNIO';
        WHEN 7  THEN SET _nombre = 'JULIO';
        WHEN 8  THEN SET _nombre = 'AGOSTO';
        WHEN 9  THEN SET _nombre = 'SEPTIEMBRE';
        WHEN 10  THEN SET _nombre = 'OCTUBRE';
        WHEN 11  THEN SET _nombre = 'NOVIEMBRE';
        WHEN 12  THEN SET _nombre = 'DICIEMBRE';
        ELSE SET _nombre = 'ERROR';
    END CASE;

    SET formato = CONCAT(_nombre, ' - ' , _year); 
    return formato;
END$$

CREATE DEFINER=`jasschos`@`localhost` FUNCTION `fc_get_codigo_cuota_financia` (`_codCto` INT(11), `_codRbo` INT(11)) RETURNS DOUBLE(10,2) BEGIN
    DECLARE v_codigoCuota INT DEFAULT null;

    SELECT FC.FCU_CODIGO
    INTO v_codigoCuota
    FROM FINANC_CUOTA AS FC
    INNER JOIN FINANCIAMIENTO AS F ON FC.FTO_CODIGO = F.FTO_CODIGO
    WHERE FC.FCU_ESTADO = 1
    AND F.CTO_CODIGO = _codCto
    ORDER BY FC.FCU_CODIGO ASC LIMIT 1;

  return v_codigoCuota;
  
END$$

CREATE DEFINER=`jasschos`@`localhost` FUNCTION `fc_get_cod_cliente` (`_cod` INT) RETURNS INT BEGIN
    DECLARE codigo INT DEFAULT 0;
    SET codigo = (SELECT CTO_CODIGO FROM RECIBO WHERE RBO_CODIGO = _cod);
    RETURN codigo;
END$$

CREATE DEFINER=`jasschos`@`localhost` FUNCTION `fc_get_importe_contrato` (`_codigo` INT(7)) RETURNS DOUBLE(10,2) BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE codServicio INT UNSIGNED;
    DECLARE v_agua double(10,2) DEFAULT 0;
    DECLARE v_desague double(10,2) DEFAULT 0;
    DECLARE v_ambos double(10,2) DEFAULT 0;
    DECLARE v_mantenimiento double(10,2) DEFAULT 0;
    DECLARE costoServicio double(10,2) DEFAULT 0;
    DECLARE _i INT UNSIGNED;
    DECLARE estadoContrato INT UNSIGNED;

    DECLARE datos CURSOR FOR
    SELECT SRV_CODIGO, TU.TUP_TARIFA_AGUA, TU.TUP_TARIFA_DESAGUE, TU.TUP_TARIFA_AMBOS, TU.TUP_TARIFA_MANTENIMIENTO
        FROM SERVICIO_CONTRATO AS SC 
        INNER JOIN CONTRATO AS C ON SC.CTO_CODIGO = C.CTO_CODIGO
        INNER JOIN TIPO_USO_PREDIO AS TU ON C.TUP_CODIGO = TU.TUP_CODIGO
        WHERE C.CTO_CODIGO = _codigo;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- ESTADO DE CONTRATO: PARA DETERMINAR SI TOMAMOS LA TARIFA DE MANTENIMIENTO
    SET estadoContrato = (SELECT CTO_ESTADO FROM CONTRATO WHERE CTO_CODIGO = _codigo);

    OPEN datos;
    SET _i = 0;
    loop_datos: LOOP
        FETCH datos INTO codServicio, v_agua, v_desague, v_ambos, v_mantenimiento;
        IF done THEN  
            LEAVE loop_datos; 
        END IF;

        SET _i = _i + 1;
        IF (_i = 1) THEN
            IF (codServicio = 1) THEN
                SET costoServicio = v_agua;
            ELSEIF (codServicio = 2) THEN
                SET costoServicio = v_desague;
            END IF;
        ELSEIF (_i = 2) THEN
            SET costoServicio = v_ambos;
        END IF;

        IF (estadoContrato = 4) THEN
            SET costoServicio = v_mantenimiento;
        END IF;
    END LOOP;
    CLOSE datos;
    RETURN costoServicio;
END$$

CREATE DEFINER=`jasschos`@`localhost` FUNCTION `fc_get_monto_financia` (`_codCto` INT(11), `_codRbo` INT(11)) RETURNS DOUBLE(10,2) BEGIN
    DECLARE v_monto double(10,2);
    
    SET v_monto = 0;
    SELECT FC.FCU_MONTO_CUOTA 
    INTO v_monto
    FROM FINANC_CUOTA AS FC
    INNER JOIN FINANCIAMIENTO AS F ON FC.FTO_CODIGO = F.FTO_CODIGO
    WHERE FC.FCU_ESTADO = 1
    AND F.CTO_CODIGO = _codCto
    ORDER BY FC.FCU_CODIGO ASC LIMIT 1;

  return v_monto;
  
END$$

CREATE DEFINER=`jasschos`@`localhost` FUNCTION `fc_get_serv_adicional_rbo` (`_cod` INT(11), `_codRbo` INT(11)) RETURNS DOUBLE(10,2) BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_cod INT;
    DECLARE v_monto double(10,2) DEFAULT 0;
    DECLARE v_suma double(10,2) DEFAULT 0;

    DECLARE montos CURSOR FOR
    SELECT SA.SAR_CODIGO, SA.SAR_COSTO 
        FROM SERVICIO_ADICIONAL_RBO AS SA
        INNER JOIN CONTRATO AS C ON SA.CTO_CODIGO = C.CTO_CODIGO
        WHERE C.CTO_CODIGO = _cod AND SA.SAR_ESTADO = 1;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN montos;
    loop_montos: LOOP
        FETCH montos INTO v_cod, v_monto;
        IF done THEN  
            LEAVE loop_montos; 
        END IF;

        SET v_suma = v_suma + v_monto;
        UPDATE SERVICIO_ADICIONAL_RBO SET SAR_CODIGO_RBO = _codRbo, SAR_ESTADO = 2 WHERE SAR_CODIGO = v_cod;
    END LOOP;

    CLOSE montos;
  return v_suma;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CAJA`
--

CREATE TABLE `CAJA` (
  `CAJ_CODIGO` int NOT NULL,
  `CAJ_NOMBRE` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `CAJ_SALDO` double(5,2) NOT NULL,
  `CAJ_CREATED` datetime NOT NULL,
  `CAJ_UPDATED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `CAJA`
--

INSERT INTO `CAJA` (`CAJ_CODIGO`, `CAJ_NOMBRE`, `CAJ_SALDO`, `CAJ_CREATED`, `CAJ_UPDATED`) VALUES
(1, 'RECURSOS DIARIOS', 0.00, '2022-02-10 00:10:48', '2022-07-02 15:50:10'),
(2, 'BANCOS', 0.00, '2022-02-10 00:10:48', '2022-02-10 00:10:48'),
(3, 'TESORERIA', 0.00, '2022-02-10 00:10:48', '2022-02-10 00:10:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CALLE`
--

CREATE TABLE `CALLE` (
  `CAL_CODIGO` int NOT NULL,
  `CAL_NOMBRE` varchar(260) COLLATE utf8_spanish_ci NOT NULL,
  `CAL_CREATED` datetime NOT NULL,
  `CAL_UPDATED` datetime NOT NULL,
  `CAL_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `CALLE`
--

INSERT INTO `CALLE` (`CAL_CODIGO`, `CAL_NOMBRE`, `CAL_CREATED`, `CAL_UPDATED`, `CAL_DELETED`) VALUES
(1, 'AV. TUPAC AMARU', '2022-02-10 00:00:00', '2022-03-23 23:04:31', NULL),
(2, 'AV. CESAR VALLEJO', '2022-02-23 19:05:34', '2022-03-23 23:02:51', NULL),
(3, 'PROLONGACIÓN CESAR VALLEJO', '2022-02-23 19:06:00', '2022-03-23 23:05:04', NULL),
(4, 'SAN LUIS', '2022-02-23 19:08:12', '2022-03-23 23:01:02', NULL),
(5, 'ANTIGUA PANAMERICANA  NORTE', '2022-02-23 19:08:53', '2022-04-01 15:37:14', NULL),
(6, 'CAJAMARCA', '2022-02-23 19:09:07', '2022-03-23 23:00:04', NULL),
(7, 'TRUJILLO', '2022-02-23 19:09:16', '2022-03-23 22:55:59', NULL),
(8, 'FRANCISCO BOLOGNESI', '2022-02-23 19:09:35', '2022-03-23 22:55:27', NULL),
(9, 'JOSE OLAYA', '2022-03-23 23:05:41', '2022-03-23 23:05:41', NULL),
(10, 'JOSE QUIÑONES', '2022-03-23 23:06:05', '2022-03-23 23:06:05', NULL),
(11, 'MANUEL PARDO', '2022-03-23 23:06:34', '2022-03-23 23:06:34', NULL),
(12, 'PUENTE GRANDE', '2022-03-23 23:07:06', '2022-03-23 23:07:06', NULL),
(13, 'MARIANO MELGAR', '2022-03-23 23:08:33', '2022-04-04 15:48:28', NULL),
(14, 'ONCE FEBRERO', '2022-03-23 23:08:49', '2022-03-23 23:08:49', NULL),
(15, 'LETICIA', '2022-03-23 23:09:07', '2022-03-23 23:09:07', NULL),
(16, 'NUEVO CHOSICA', '2022-03-23 23:09:37', '2022-03-23 23:09:37', NULL),
(17, 'SANTA ANITA', '2022-03-23 23:09:54', '2022-03-23 23:09:54', NULL),
(18, 'MIGUEL GRAU', '2022-03-23 23:10:14', '2022-03-23 23:10:14', NULL),
(19, 'PALMO', '2022-03-23 23:10:31', '2022-03-23 23:10:31', NULL),
(20, 'ANDRES RAZURI', '2022-03-23 23:11:43', '2022-03-23 23:11:43', NULL),
(21, 'ALGARROBOS', '2022-03-23 23:12:11', '2022-03-23 23:12:11', NULL),
(22, 'ARICA', '2022-03-23 23:12:23', '2022-03-23 23:12:23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CLIENTE`
--

CREATE TABLE `CLIENTE` (
  `CLI_CODIGO` int NOT NULL,
  `CLI_DOCUMENTO` varchar(13) COLLATE utf8_spanish_ci NOT NULL,
  `CLI_NOMBRES` varchar(260) COLLATE utf8_spanish_ci NOT NULL,
  `CLI_DIRECCION` varchar(260) COLLATE utf8_spanish_ci NOT NULL,
  `CLI_FECHA_NAC` date DEFAULT NULL,
  `CLI_DISTRITO` varchar(70) COLLATE utf8_spanish_ci NOT NULL,
  `CLI_PROVINCIA` varchar(70) COLLATE utf8_spanish_ci NOT NULL,
  `CLI_DEPARTAMENTO` varchar(70) COLLATE utf8_spanish_ci NOT NULL,
  `CLI_EMAIL` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CLI_TELEFONO` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CLI_TIPO` int NOT NULL,
  `CLI_REPRES_LEGAL` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CLI_CREATED` datetime NOT NULL,
  `CLI_UPDATED` datetime NOT NULL,
  `CLI_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `CLIENTE`
--

INSERT INTO `CLIENTE` (`CLI_CODIGO`, `CLI_DOCUMENTO`, `CLI_NOMBRES`, `CLI_DIRECCION`, `CLI_FECHA_NAC`, `CLI_DISTRITO`, `CLI_PROVINCIA`, `CLI_DEPARTAMENTO`, `CLI_EMAIL`, `CLI_TELEFONO`, `CLI_TIPO`, `CLI_REPRES_LEGAL`, `CLI_CREATED`, `CLI_UPDATED`, `CLI_DELETED`) VALUES
(1, '27571646', 'ACUÑA VASQUEZ ELVIA', 'AV.TUPAC AMARU S/N - Mz N - LT.02', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '978863059', 1, NULL, '2022-02-10 10:59:00', '2022-03-22 16:02:26', NULL),
(2, '16532088', 'ALCIRA GOMEZ YOMONA VDA.DE MACO', 'Av. Tupac Amaru S/N - MZ 9 - LT 3', '1950-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '976610095', 1, NULL, '2022-02-10 11:02:10', '2022-03-22 16:16:04', NULL),
(3, '16759460', 'AYASTA SIGNOL ANDREA', 'Av. Tupac Amaru 511 - MZ 14 - LT 4-B', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '978181643', 1, NULL, '2022-02-10 11:06:05', '2022-03-22 16:23:20', NULL),
(4, '41070413', 'AZABACHE ENEQUE LILIANA DEL PILAR', 'AV. TUPAC AMARU N° 439 MZ. 10 LT.24', '1982-07-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '938303083', 1, NULL, '2022-02-10 11:08:15', '2022-03-28 21:11:15', NULL),
(5, '16595564', 'BAUTISTA ALDANA JESUS', 'Av. Tupac Amaru 325 - MZ 6 - LT 14', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '924103592', 1, NULL, '2022-02-10 11:17:38', '2022-03-22 16:47:29', NULL),
(6, '16471075', 'CABREJOS GUEVARA ANGELICA ELIZABETH', 'Av. Tupac Amaru S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979974855', 1, NULL, '2022-02-10 11:19:59', '2022-03-22 17:03:27', NULL),
(7, '42714629', 'CAMPOS  TORRES LUCY MARLENY', 'AV. TUPAC AMARU N° 463 MZ.10 LT.13', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '971305470', 1, NULL, '2022-02-10 11:23:47', '2022-04-25 15:56:51', NULL),
(8, '27847508', 'CARRASCO LEON HÉCTOR FERNANDO', 'Av. Tupac Amaru  S/N - MZ 9 - LT 4B', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 11:26:10', '2022-03-22 17:26:16', NULL),
(9, '16559037', 'CARRILLO DE EFIO CONSUELO DE LA ENCARNACION', 'Av. Tupac Amaru  S/N - MZ 8 - LT 18', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 11:27:57', '2022-03-22 17:28:48', NULL),
(10, '27429493', 'CLAVO MONDRAGON LEONCIO', 'Av. Tupac Amaru  419 - MZ 8 - LT 14', '1979-10-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '982183199', 1, NULL, '2022-02-10 11:31:26', '2022-03-22 17:38:24', NULL),
(11, '16651153', 'CORDOVA CERNA DE YALICO CLAUDIA', 'AV. TUPAC AMARU S/N - LT.A', '1956-07-10', 'LA VICTORIA', 'LA VICTORIA', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-10 11:33:43', '2022-02-10 16:53:25', NULL),
(12, '16555455', 'CUSTODIO AYASTA AGUSTIN', 'Av. Tupac Amaru  507 - MZ 14 - LT 4', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '942856500', 1, NULL, '2022-02-10 15:10:22', '2022-03-22 18:18:29', NULL),
(13, '16594481', 'CUSTODIO GUZMAN MANUEL', 'Av. Tupac Amaru  533  MZ 16 - LT 3-D', '1965-04-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '97843683', 1, NULL, '2022-02-10 15:12:22', '2022-03-22 18:24:36', NULL),
(14, '27372406', 'DIAZ CARRANZA MANUEL JESUS', 'Av. Tupac Amaru  S/N - MZ 5', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 15:14:47', '2022-03-22 18:55:50', NULL),
(15, '20511497184', 'VIPETROS S.A.C', 'AV. TUPAC AMARU S/N', NULL, 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'grifo_reque@gasolinasdeamerica.com', '979296021', 2, 'MATOS SIFUENTES SAMM MAURICIO AAROM', '2022-02-10 15:22:11', '2022-02-10 15:22:11', NULL),
(16, '16544467', 'ESPINOZA CUSTODIO NICOLASA', 'Av. Tupac Amaru  509 - MZ 14 - LT 4-A', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 15:25:15', '2022-03-22 19:16:27', NULL),
(17, '16556938', 'ESPINOZA NICOLAS JULIO', 'AV. TUPAC AMARU N° 217', '1973-11-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-10 15:27:13', '2022-04-17 23:12:59', NULL),
(18, '80251486', 'FERNANDEZ ALTAMIRANO MARIA LIDIA', 'Av. Tupac Amaru 461 - MZ 10 - LT 14', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 15:30:31', '2022-03-22 19:35:18', NULL),
(19, '16588051', 'GARCIA TANCUN LUISA', 'Av. Tupac Amaru S/N - MZ 12	 - LT 1', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '951699653', 1, NULL, '2022-02-10 15:33:11', '2022-03-22 20:16:06', NULL),
(20, '42577777', 'GONZALES ENEQUE ANELY CECILIA', 'Av. Tupac Amaru  S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 15:34:51', '2022-03-22 20:35:59', NULL),
(21, '16595803', 'GONZALES REYES JUANA', 'Av. Tupac Amaru  413 - MZ 8 - LT 11', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '956060783', 1, NULL, '2022-02-10 15:36:54', '2022-03-22 20:59:29', NULL),
(22, '16595387', 'GONZALES RODRIGUEZ JOSE', 'Av. Tupac Amaru 515 - MZ 14 - LT 14-E', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '976477603', 1, NULL, '2022-02-10 15:38:43', '2022-03-22 21:02:23', NULL),
(23, '42390748', 'GONZALES VILLEGAS LUIS ORLANDO', 'Av. Tupac Amaru  S/N - MZ 1B - LT 4', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979650053', 1, NULL, '2022-02-10 15:40:26', '2022-03-22 21:05:19', NULL),
(24, '40528696', 'GUZMAN SUCLUPE JUANA BERTHA', 'Av. Tupac Amaru  S/N - MZ 6 - LT 15', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979487448', 1, NULL, '2022-02-10 15:42:34', '2022-04-22 17:30:19', NULL),
(25, '16594492', 'GUZMAN QUISPE MARIA ALEJANDRINA', 'Av. Tupac Amaru  225 - Mz C - LT 2', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '999165157', 1, NULL, '2022-02-10 15:44:50', '2022-03-22 21:13:32', NULL),
(26, '16595890', 'LAINES MECHAN GREGORIO', 'Av. Tupac Amaru 329 - MZ 6 - LT 16', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '976857411', 1, NULL, '2022-02-10 15:46:55', '2022-03-22 23:17:51', NULL),
(27, '16595923', 'LLONTOP AZABACHE JOSEFA', 'Av. Tupac Amaru  435 - MZ 8 - LT 22', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 15:50:08', '2022-03-22 23:30:59', NULL),
(28, '40682995', 'LLONTOP MIO HAYDEE MILAGROS', 'Av. Tupac Amaru  S/N - MZ 12 - LT 9', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 15:52:29', '2022-03-22 23:53:15', NULL),
(29, '45729605', 'LLONTOP RODRIGUEZ DINA CONCEPCION', 'Av. Tupac Amaru  471 - MZ 10 - LT 17', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 15:54:22', '2022-03-23 00:16:21', NULL),
(30, '16554963', 'MECHAN GONZALES MANUEL', 'AV. TUPAC AMARU N° 353 - MZ. 6 LT.28', '1957-10-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '074431993', 1, NULL, '2022-02-10 15:56:07', '2022-03-29 21:06:31', '2022-03-29 21:06:31'),
(31, '80551603', 'MIO MORALES ANGELITA', 'Av. Tupac Amaru  491 - MZ 12 - LT 9-A', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '954650821', 1, NULL, '2022-02-10 15:57:44', '2022-03-23 01:31:34', NULL),
(32, '16754780', 'MUÑOZ BARDALES EDGARD', 'Av. Tupac Amaru  S/N - MZ 8 - LT 15-A', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '948694195', 1, NULL, '2022-02-10 16:00:53', '2022-03-23 01:49:01', NULL),
(33, '16481006', 'NAZARIO AHUMADA LUIS', 'Av. Tupac Amaru  S/N - MZ 14 - LT 1-A', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 16:03:48', '2022-03-23 01:54:41', NULL),
(34, '16769674', 'PESCORAN GARCIA MARIBEL', 'Av. Tupac Amaru  478', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 16:05:57', '2022-03-23 02:23:47', NULL),
(35, '06876910', 'PISFIL CASTILLO NATIVIDAD', 'Av. Tupac Amaru  301 - MZ 6 - LT 1', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '998869547', 1, NULL, '2022-02-10 16:07:46', '2022-03-23 02:27:39', NULL),
(36, '16595934', 'RODRIGUEZ CUSTODIO MANUELA', 'Av. Tupac Amaru  S/N - MZ 12 - LT 7', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 16:09:13', '2022-03-23 03:16:37', NULL),
(37, '16598102', 'RODRIGUEZ MECHAN GUADALUPE', 'Av. Tupac Amaru  S/N - MZ 16 - LT 3-C1', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 16:10:47', '2022-03-23 03:31:13', NULL),
(38, '17443288', 'ROJAS PAZ GUADALUPE', 'Av. Tupac Amaru 213 - MZ 2 - LT 1', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '967688224', 1, NULL, '2022-02-10 16:12:20', '2022-03-28 23:54:52', NULL),
(39, '80512799', 'SALAZAR CHAVESTA MERCEDES MARIA', 'Av. Tupac Amaru  S/N', '2000-01-01', 'LA VICTORIA', 'LA VICTORIA', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 16:13:36', '2022-03-23 03:45:05', NULL),
(40, '42710906', 'SALAZAR GONZALES CESAR AUGUSTO', 'Av. Tupac Amaru  S/N', '2000-01-01', 'LA VICTORIA', 'LA VICTORIA', 'LAMBAYEQUE', 'jasschosica@gmail.com', '910361328', 1, NULL, '2022-02-10 16:14:49', '2022-03-23 03:54:44', NULL),
(41, '16777014', 'SANCHEZ SANCHEZ ROSA', 'Av. Tupac Amaru  440 - MZ 10 - LT 1', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '920820613', 1, NULL, '2022-02-10 16:17:54', '2022-03-23 04:00:33', NULL),
(42, '16721324', 'TORRES ISIQUE CESAR', 'Av. Tupac Amaru  425 - MZ 8 - LT 16-A', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '956487150', 1, NULL, '2022-02-10 16:20:05', '2022-03-23 04:23:10', NULL),
(43, '16595830', 'VELASQUEZ CUSTODIO MAXIMO', 'Av. Tupac Amaru 415 - MZ 8 - LT 12', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '949532964', 1, NULL, '2022-02-10 16:21:44', '2022-03-23 04:34:52', NULL),
(44, '40247120', 'VELASQUEZ LLUEN WILLIAM', 'Av. Tupac Amaru  S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 16:23:24', '2022-03-23 04:49:14', NULL),
(45, '16594432', 'VELASQUEZ REYES MANUEL', 'Av. Tupac Amaru  429 - MZ 8 - LT 17', '1947-02-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '963636907', 1, NULL, '2022-02-10 16:25:42', '2022-03-23 05:21:29', NULL),
(46, '40346173', 'VELASQUEZ OLIDEN WALTER RICHARD', 'Av. Tupac Amaru  413 - MZ10 - LT 7 B', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '963636907', 1, NULL, '2022-02-10 16:32:50', '2022-03-24 15:10:34', NULL),
(47, '16594569', 'VILLEGAS GUERRA RICARDO', 'Av. Tupac Amaru  311 - MZ 6 -LT 7', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '957466566', 1, NULL, '2022-02-10 16:47:52', '2022-03-23 05:28:47', NULL),
(48, '46284027', 'BUSTAMANTE ASCENCIO LUIS BENJAMIN', 'AV. CESAR VALLEJO S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 16:55:23', '2022-03-29 21:14:16', '2022-03-29 21:14:16'),
(49, '43223493', 'CARHUATANTA DE GAMARRA ELIZABETH', 'AV. CESAR VALLEJO N° 127', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979039985', 1, NULL, '2022-02-10 16:56:49', '2022-03-29 21:15:42', '2022-03-29 21:15:42'),
(50, '41014700', 'CENTURION TANTALEAN JUSTO', 'AV. CESAR VALLEJO  269 - INT.B', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 16:59:16', '2022-03-29 21:16:19', '2022-03-29 21:16:19'),
(51, '16593628', 'CUBAS ROJAS ANDREA', 'AV. CESAR VALLEJO N° 345', '1954-10-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '970982767', 1, NULL, '2022-02-10 17:01:10', '2022-03-29 21:16:48', '2022-03-29 21:16:48'),
(52, '16594537', 'DIAZ  SEGOVIA HILARIO', 'AV. TUPAC AMARU N° 125', '1974-11-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '074431993', 1, NULL, '2022-02-10 17:02:54', '2022-03-29 21:19:07', '2022-03-29 21:19:07'),
(53, '16474684', 'DIAZ VILLEGAS JULIO', 'AV. CESAR VALLEJO N° 246', '1951-09-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '074431993', 1, NULL, '2022-02-10 17:04:40', '2022-03-29 21:19:35', '2022-03-29 21:19:35'),
(54, '16717189', 'FARRO AYASTA MANUEL', 'AV. CESAR VALLEJO S/N - MZ. 09 LT.05', '1964-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '978664940', 1, NULL, '2022-02-10 17:08:37', '2022-03-29 21:20:11', '2022-03-29 21:20:11'),
(55, '16595810', 'FERNANDEZ RIOJA EUSEBIO', 'AV. CESAR VALLEJO N° 390', '1965-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '994096806', 1, NULL, '2022-02-10 17:09:57', '2022-03-29 21:21:01', '2022-03-29 21:21:01'),
(56, '16594463', 'GONZALES ESPINOZA MANUEL', 'AV. CESAR VALLEJO N° 206', '1964-08-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '074431993', 1, NULL, '2022-02-10 17:11:18', '2022-03-29 21:21:52', '2022-03-29 21:21:52'),
(57, '16773299', 'GONZALES RAMIREZ MARIA ELENA', 'AV. CESAR VALLEJO S/N', '1975-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '979830154', 1, NULL, '2022-02-10 17:12:33', '2022-03-29 21:22:36', '2022-03-29 21:22:36'),
(58, '80268581', 'DIAZ CUEVA LUISA CRISTINA', 'AV. CESAR VALLEJO N° 318', '1981-03-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '918857681', 1, NULL, '2022-02-10 17:13:53', '2022-03-29 21:23:21', '2022-03-29 21:23:21'),
(59, '16594485', 'GUZMAN AGAPITO MAXIMO', 'AV. CESAR VALLEJO N° 307', '1949-11-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '979501183', 1, NULL, '2022-02-10 17:15:34', '2022-03-29 21:23:58', '2022-03-29 21:23:58'),
(60, '16615628', 'GUZMAN SAMILLAN JUANA', 'AV. CESAR VALLEJO S/N - MZ.05 LT.15', '1963-05-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '920266458', 1, NULL, '2022-02-10 17:17:16', '2022-03-29 21:24:22', '2022-03-29 21:24:22'),
(61, '16595807', 'HORNA FARROÑAY VIOLETA', 'AV. CESAR VALLEJO N° 148 - MZ.05 LT.15', '1943-02-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '074431993', 1, NULL, '2022-02-10 17:19:31', '2022-03-29 21:24:41', '2022-03-29 21:24:41'),
(62, '48790425', 'HERMIDA PEÑA CARLOS ANDRES', 'AV. CESAR VALLEJO N° 127', '1957-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '074431993', 1, NULL, '2022-02-10 17:20:48', '2022-03-29 21:25:03', '2022-03-29 21:25:03'),
(63, '16595837', 'IGNACIO CAVERO MERCEDES', 'AV. CESAR VALLEJO N° 141', '1950-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '941941672', 1, NULL, '2022-02-10 17:22:07', '2022-03-29 21:25:30', '2022-03-29 21:25:30'),
(64, '16594480', 'LLUEN GUZMAN EMILIANA', 'AV. CESAR VALLEJO S/N - MZ.21 LT.15', '1955-02-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '950591688', 1, NULL, '2022-02-10 17:26:03', '2022-03-29 21:25:49', '2022-03-29 21:25:49'),
(65, '16691878', 'LLUEN PISFIL JOSE SANTOS', 'AV. CESAR VALLEJO N° 270', '1979-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '979868900', 1, NULL, '2022-02-10 17:27:27', '2022-03-29 21:26:11', '2022-03-29 21:26:11'),
(66, '16560284', 'LLUEN PISFIL SILVIA MARILU', 'AV. CESAR VALLEJO N° 131', '1969-03-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-10 17:29:06', '2022-04-21 01:11:13', NULL),
(67, '16594405', 'LLUEN PISFIL VICTOR RAUL', 'AV. CESAR VALLEJO N° 300', '1960-10-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '912790285', 1, NULL, '2022-02-10 17:33:48', '2022-03-29 21:27:37', '2022-03-29 21:27:37'),
(68, '16594428', 'MARCELO YAUCE MARINA', 'AV. CESAR VALLEJO N° 250', '1939-02-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '074431993', 1, NULL, '2022-02-10 17:35:47', '2022-03-29 21:28:01', '2022-03-29 21:28:01'),
(69, '16598204', 'MIRANDA DE GONZALES CARMEN', 'AV. CESAR VALLEJO N° 210', '1940-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '074431993', 1, NULL, '2022-02-10 17:37:13', '2022-03-29 21:28:17', '2022-03-29 21:28:17'),
(70, '16628174', 'OBANDO CAPUÑAY BLANCA', 'AV. CESAR VALLEJO N° 202', '1970-05-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '978246099', 1, NULL, '2022-02-10 17:38:40', '2022-03-29 21:28:36', '2022-03-29 21:28:36'),
(71, '16778106', 'PISFIL CHANCAFE AGUSTIN', 'AV. CESAR VALLEJO N° 313', '1950-10-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '901722157', 1, NULL, '2022-02-10 17:47:16', '2022-03-29 21:29:16', '2022-03-29 21:29:16'),
(72, '16596407', 'RODRIGUEZ VARGAS CARLOS A.', 'AV. CESAR VALLEJO N° 239', '1952-10-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '940344914', 1, NULL, '2022-02-10 17:49:08', '2022-03-29 21:29:34', '2022-03-29 21:29:34'),
(73, '16593626', 'RODRIGUEZ VARGAS HUGO', 'AV. CESAR VALLEJO N° 249', '1960-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '951484196', 1, NULL, '2022-02-10 17:50:32', '2022-03-29 21:29:53', '2022-03-29 21:29:53'),
(74, '16637115', 'SENMACHE GONZALES JORGE', 'AV, CESAR VALLEJO N° 207 - INT.A', '1976-02-10', 'LA VICTORIA', 'LA VICTORIA', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-10 17:52:27', '2022-03-29 21:31:27', '2022-03-29 21:31:27'),
(75, '16636593', 'SENMACHE GONZALES MANUELA', 'AV. CESAR VALLEJO # 207 - LT 1', '1967-02-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-10 17:57:48', '2022-04-08 16:41:48', NULL),
(76, '16551688', 'SENMACHE GONZALES ROSA', 'AV. CESAR VALLEJO N° 207 - INT. C', '1976-02-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '', 1, NULL, '2022-02-10 18:02:17', '2022-03-29 21:32:19', '2022-03-29 21:32:19'),
(77, '16636876', 'SENMACHE GONZALES WALTER', 'AV. CESAR VALLEJO N° 207 - INT. D', '1966-10-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-10 18:03:29', '2022-03-29 21:32:43', '2022-03-29 21:32:43'),
(78, '55444777', 'DELGADO LINARES ROBERTO', 'JOSE QUIÑONES S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'JASSCHOSICA@GMAIL.COM', '074-431993', 1, NULL, '2022-02-10 18:05:56', '2022-04-07 12:58:27', NULL),
(79, '28063711', 'VELEZMORO VARGAS WILSON', 'AV. CESAR VALLEJO N° 238', '1960-06-10', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', '', '965675779', 1, NULL, '2022-02-10 18:10:20', '2022-03-29 21:34:01', '2022-03-29 21:34:01'),
(80, '16426512', 'ODAR MENDOZA JORGE LUIS', 'Av. Tupac Amaru  307 - MZ 6 - LT 5 /6', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '938173638', 1, NULL, '2022-02-12 09:45:16', '2022-04-08 17:00:59', NULL),
(81, '16594462', 'ACUÑA RAMOS RUPERTO', 'Av. Túpac Amaru 355 - MZ 8 - LT1', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:05:43', '2022-03-22 17:36:09', NULL),
(82, '16594437', 'AZABACHE GONZALES AQUILINO', 'Av. Tupac Amaru 421- MZ 8 - LT 15', '1951-06-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-21 12:07:32', '2022-03-22 16:33:06', NULL),
(83, '16594504', 'CAICEDO ARCE OSCAR', 'Av. Tupac Amaru 247', '1963-08-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:08:57', '2022-03-22 17:05:19', NULL),
(84, '16474572', 'COLCHON ENEQUE ROGELIA', 'V. TUPAC AMARU N° 423 . MZ.8 LT.158', '1968-03-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:10:21', '2022-04-08 17:16:17', NULL),
(85, '16594517', 'CUSTODIO GUZMAN AURELIO', 'Av. Tupac Amaru  345 -MZ 8 - LT 7', '1949-08-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:11:48', '2022-03-22 18:21:06', NULL),
(86, '16637095', 'FLORES HORNA LUIS ALBERTO', 'Av. Tupac Amaru  299', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:13:14', '2022-03-22 19:42:34', NULL),
(87, '16595857', 'FLORES SOSA JOSE', 'AV. TUPAC AMARU N° 447 - MZ.10 LT.5.', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:14:26', '2022-04-08 17:27:27', NULL),
(88, '16596605', 'GAMARRA TULLUME CARMEN', 'Av. Tupac Amaru  S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:15:39', '2022-03-22 20:05:59', NULL),
(89, '16555351', 'GAMARRA TULLUME PEDRO', 'Av. Tupac Amaru  257', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:16:46', '2022-03-22 20:09:34', NULL),
(90, '02692303', 'GARCIA GARCIA CONFESORA', 'Av. Tupac Amaru  224', '1944-05-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:18:07', '2022-03-22 20:11:39', NULL),
(91, '16594433', 'GONZALES CHUMIOQUE JOSE', 'Av. TUPAC AMARU  N° 417 MZ.8 LT.12-A', '1961-02-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-21 12:19:41', '2022-02-21 12:23:15', NULL),
(92, '16594413', 'GONZALES GUZMAN INOCENTE', 'Av. Tupac Amaru  317 - MZ 6 - LT 10', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:24:21', '2022-03-22 20:46:32', NULL),
(93, '16754614', 'GONZALES REYES JUAN', 'Av. Tupac Amaru  S/N - MZ 8 - LT 10', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:25:34', '2022-03-22 20:53:40', NULL),
(94, '16417622', 'HERRERA ALVINES FRANCISCO', 'Av. Tupac Amaru  S/N - MZ 10 - LT 8', '1956-02-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:26:51', '2022-03-22 21:16:08', NULL),
(95, '16680375', 'IDROGO VALLEJOS MARITA', 'Av. Tupac Amaru  S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:27:52', '2022-03-22 23:15:19', NULL),
(96, '16551290', 'LAINES MECHAN PEDRO', 'Av. Tupac Amaru  313 - MZ 6 - LT 8', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:29:09', '2022-03-22 23:19:58', NULL),
(97, '16426677', 'LLONTOP AZABACHE EDITH MIRTHA', 'Av. Tupac Amaru  S/N - MZ 8 - LT 20', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:30:18', '2022-03-22 23:28:41', NULL),
(98, '16705535', 'MECHAN AGAPITO CESAR AUGUSTO', 'AV. TUPAC AMARU N° 351 - MZ. 6 LT.27', '1954-02-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-21 12:32:09', '2022-03-28 20:40:07', NULL),
(99, '16595882', 'MIO MORALES PAULA', 'Av. Tupac Amaru  309 - MZ 6 - LT 6', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:33:33', '2022-03-23 01:38:54', NULL),
(100, '16595818', 'ORTIZ IDROGO ESPERANZA', 'Av. Tupac Amaru  343 - MZ 8 - LT 6', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:34:58', '2022-03-23 02:12:33', NULL),
(101, '16594423', 'REYES SANCHEZ MARIA', 'Av. Tupac Amaru 345 - MZ 6 - LT 24', '1932-03-21', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:36:21', '2022-03-23 03:03:45', NULL),
(102, '16594422', 'RODRIGUEZ VDA. DE VELASQUEZ SANTOS', 'Av. Tupac Amaru  321 - MZ 6 - LT 12', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:37:49', '2022-03-23 03:36:56', NULL),
(103, '16444394', 'TORRES LLUEN SIMON', 'Av. Tupac Amaru  445 - MZ 10 - LT 4', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:39:35', '2022-03-23 04:25:50', NULL),
(104, '16497827', 'UCHOFEN MONJA JOSE', 'Av. Tupac Amaru  333 - MZ 6- LT 19', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:44:01', '2022-03-23 04:31:17', NULL),
(105, '16555341', 'VELASQUEZ ESPINOZA BARTOLOME', 'Av. Túpac Amaru 203', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:45:42', '2022-03-23 04:38:05', NULL),
(106, '16595942', 'VELASQUEZ ESPINOZA JUAN', 'Av. Tupac Amaru  241', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:46:39', '2022-03-23 04:41:11', NULL),
(107, '16728525', 'VELASQUEZ OLIDEN CESAR', 'Av. Tupac Amaru  451 - MZ 10 - LT 7A', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979957565', 1, NULL, '2022-02-21 12:48:22', '2022-03-24 15:07:14', NULL),
(108, '16595877', 'VELASQUEZ RODRIGUEZ JOSE', 'Av. Tupac Amaru 303 - MZ 6 - LT 2', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-21 12:52:32', '2022-04-25 14:49:25', NULL),
(109, '16748894', 'VILLEGAS ENEQUE JOSE CLEMENTE', 'Av. Tupac Amaru  315 - MZ 6 - LT 9', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '9508924812', 1, NULL, '2022-02-21 12:54:09', '2022-03-23 05:26:29', NULL),
(110, '16636558', 'MECHAN GONZALES TRINIDAD', 'Av. Tupac Amaru 479 - MZ 12 - LT 3', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 11:50:41', '2022-03-23 01:03:32', NULL),
(111, '16595918', 'AZABACHE GAMARRA SANTIAGO', 'Av. Tupac Amaru 459 - MZ 10 - LT 11', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-22 11:57:58', '2022-03-22 16:30:43', NULL),
(112, '16597833', 'LLONTOP GONZALES DOLORES', 'Av. Tupac Amaru  S/N - MZ 12 - LT 9-B', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 12:06:05', '2022-03-22 23:50:26', NULL),
(113, '16630970', 'MELENDEZ ASCURRA LILIANA', 'Av. Tupac Amaru 517 - MZ 14 - LT 4-F', '1958-07-22', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 12:09:28', '2022-03-23 01:08:57', NULL),
(114, '16595833', 'GAMARRA TULLUME FELICITA', 'Av. Tupac Amaru 263', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 12:32:44', '2022-03-22 20:03:37', NULL),
(115, '16463228', 'DELGADO PEREZ MARIO', 'Av. Tupac Amaru  S/N - MZ 4 - LT 4-A', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 12:36:45', '2022-03-22 18:47:31', NULL),
(116, '16594507', 'ENEQUE FLORES MERCEDES', 'Av. Tupac Amaru  S/N - MZ 10 - LT 9', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 12:43:17', '2022-03-22 19:08:35', NULL),
(117, '16766799', 'ZAVALA MUÑOZ GLORIS', 'Av. Tupac Amaru  S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 12:51:56', '2022-03-23 05:35:37', NULL),
(118, '40487922', 'TANTALEAN HURTADO PABLO', 'Av. Tupac Amaru  449 - MZ 10 - LT 6', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 12:55:35', '2022-03-23 04:19:56', NULL),
(119, '16761756', 'TAIPE ZANABRIA MAXIMO', 'Av. Tupac Amaru  441 - MZ 10 - LT 2', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 12:58:12', '2022-03-23 04:16:39', NULL),
(120, '41695028', 'PEÑA LLONTOP VIVIANA MARITHE', 'Av. Tupac Amaru  S/N - MZ 6 - LT 3', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 13:03:16', '2022-03-23 02:36:30', NULL),
(121, '16598339', 'MORALES AYASTA LUIS', 'Av. Tupac Amaru  473 - MZ 10 - LT 18', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '943480447', 1, NULL, '2022-02-22 13:06:04', '2022-03-23 01:45:33', NULL),
(122, '16452816', 'LLONTOP ENEQUE JUANA ROSA', 'AV.TUPAC AMARU N° 469 - MZ. 10 LT.16', '1967-07-22', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-22 13:11:21', '2022-03-29 01:00:49', NULL),
(123, '43694023', 'LLONTOP MIO CINTIA', 'Av. Tupac Amaru 435 - MZ 8 - LT 20-A', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 13:13:21', '2022-03-23 00:12:14', NULL),
(124, '16594457', 'MIÑOPE ÑAÑEZ MARIA', 'Av. Tupac Amaru  341 - MZ 6 - LT 22', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 13:17:41', '2022-03-23 01:26:56', NULL),
(125, '16615648', 'MIÑOPE GONZALES ANDREA', 'Av. Tupac Amaru  409 - MZ 8 - LT 8', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 13:19:39', '2022-03-23 01:23:21', NULL),
(126, '20517668', 'RD RENTAL S.A.C', 'Av. Tupac Amaru  S/N - MZ C - LT 6', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 14:25:38', '2022-03-23 02:50:30', NULL),
(127, '16593659', 'AYASTA AGAPITO RAFAEL', 'AV. CESAR VALLEJO S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 14:30:43', '2022-03-29 21:57:54', '2022-03-29 21:57:54'),
(128, '16594469', 'CAVERO VDA. DE IGNACIO RITA VIOLETA', 'AV. CESAR VALLEJO S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 14:32:30', '2022-03-29 21:58:29', '2022-03-29 21:58:29'),
(129, '16691889', 'CENTURION TANTALEAN SEGUNDO', 'AV. CESAR VALLEJO N° 269', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 15:20:05', '2022-03-29 21:59:02', '2022-03-29 21:59:02'),
(130, '16621189', 'CENTURION TANTALEN MARIA ROSA', 'AV.CESAR VALLEJO N° 269', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-22 15:21:14', '2022-03-29 21:59:28', '2022-03-29 21:59:28'),
(131, '16596787', 'CORNEJO DE AZABACHE JUANA', 'AV. CESAR VALLEJO S/N', '1967-06-22', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-22 15:22:38', '2022-03-29 21:59:42', '2022-03-29 21:59:42'),
(132, '16595817', 'DIAZ BENAVIDEZ AMADO', 'AV. CESAR VALLEJO S/N', '1959-07-22', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-22 15:26:24', '2022-03-29 22:00:00', '2022-03-29 22:00:00'),
(133, '16644053', 'DIAZ VILLEGAS ARISTIDES', 'AV. CESAR VALLEJO S/N', '1961-03-22', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-22 15:27:32', '2022-03-29 22:00:17', '2022-03-29 22:00:17'),
(134, '16595557', 'DIAZ VILLEGAS HELMER', 'AV. CESAR VALLEJO N° 350', '1949-03-22', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-22 15:30:11', '2022-03-29 22:00:35', '2022-03-29 22:00:35'),
(135, '16552354', 'EFFIO DE GAMARRA MARTINA', 'AV. CESAR VALLEJO S/N', '1949-06-22', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-22 15:31:18', '2022-03-29 22:00:56', '2022-03-29 22:00:56'),
(136, '09195912', 'VELASQUEZ GARCIA MARIA LUZ', 'Av. Tupac Amaru  S/N - MZ 8 - LT 13', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-23 11:05:59', '2022-03-23 04:45:05', NULL),
(137, '07734743', 'YLLATOPA RAMIREZ SERGIO ANTONIO', 'Av. Tupac Amaru  S/N - MZ 7 - LT 4', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979004550', 1, NULL, '2022-02-23 12:28:27', '2022-03-23 05:34:11', NULL),
(138, '16598380', 'CENTRO BAMBAMARCA -SEDE CHICLAYO', 'Av. Tupac Amaru  515', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-23 12:43:49', '2022-03-22 17:33:27', NULL),
(139, '16467475', 'DE LA TORRE UGARTE DE ALARCON ESTHER', 'Av. Tupac Amaru  S/N - MZ 16 - LT F', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-23 13:35:54', '2022-03-22 18:39:31', NULL),
(140, '43361064', 'BARRUETO SANCHEZ JOSE', 'Av. Tupac Amaru S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '999938307', 1, NULL, '2022-02-23 13:51:55', '2022-03-22 16:44:59', NULL),
(141, '16481872', 'GONZALES ENEQUE ASTERIO', 'Av. Tupac Amaru 481 - MZ 12 - LT 4', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-23 14:05:09', '2022-03-22 20:40:18', NULL),
(142, '16598103', 'AZABACHE LLUEN JULIO', 'AV. CESAR VALLEJO S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '00000000', 1, NULL, '2022-02-23 15:32:27', '2022-03-29 22:05:24', '2022-03-29 22:05:24'),
(143, '16597599', 'GARNIQUE BALLENA ANDRES', 'AV. TUPAC AMARU N° 349 - MZ.8 LT.26', '1973-11-23', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-23 16:36:20', '2022-03-24 15:20:43', NULL),
(144, '16597445', 'GREGORIO DE LOPEZ PAULA', 'Av. Tupac Amaru  347 - MZ 6 - LT 25', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-23 16:47:23', '2022-03-22 21:07:42', NULL),
(145, '16465341', 'HORNA FARROÑAY ALEJANDRO', 'Av. Tupac Amaru  299', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-23 16:53:44', '2022-03-22 21:18:35', NULL),
(146, '17537319', 'LLONTOP AZABACHE MANUEL', 'Av. Tupac Amaru  S/N - MZ 8 - LT 19', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-23 17:04:09', '2022-03-22 23:33:44', NULL),
(147, '16554965', 'MECHAN CHAVESTA MANUEL', 'AV. TUPAC AMARU N° 353 - MZ.6 LT.28', '1940-02-23', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '074431993', 1, NULL, '2022-02-23 17:35:25', '2022-04-25 17:22:58', NULL),
(148, '16722480', 'ARISTA MONZAMBITE ROMULO', 'Av. Tupac Amaru  S/N', '2000-01-01', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-23 18:29:41', '2022-04-25 12:30:23', NULL),
(149, '16559003', 'AGAPITO CAPUÑAY LUCRECIA', 'AV. TUPAC AMARU N° 467 - MZ. 10 LT.15', '1967-06-24', 'LA VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-02-24 10:36:59', '2022-03-22 15:20:48', '2022-03-22 15:20:48'),
(150, '10000000', 'ALEJANDRIA VILCHEZ WILMA', 'Av. Tupac Amaru S/N - MZ 16 - LT 3-B', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 10:47:43', '2022-03-22 15:20:43', '2022-03-22 15:20:43'),
(151, '10000001', 'BARDALES AREVALO TEDIL', 'AV.TUPAC AMARU S/N', '0001-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 10:57:06', '2022-03-22 15:20:36', '2022-03-22 15:20:36'),
(152, '10000002', 'BARRAGAN GUERRERO ALBERTO', 'AV. TUPAC AMARU S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:00:15', '2022-03-22 15:20:25', '2022-03-22 15:20:25'),
(153, '10000003', 'BURGA PECSEN FIORELLA', 'Av. Tupac Amaru S/N - MZ 14 - LT 3', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:06:00', '2022-03-22 15:20:19', '2022-03-22 15:20:19'),
(154, '10000004', 'CORONEL CASTILLO CLARIZA', 'Av. Tupac Amaru S/N - MZ  M - LT 8', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:11:49', '2022-03-22 15:20:11', '2022-03-22 15:20:11'),
(155, '10000005', 'COTRINA ORTIZ OSCAR  LT.B', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:19:02', '2022-03-22 15:20:04', '2022-03-22 15:20:04'),
(156, '10000006', 'COTRINA ORTIZ OSCAR LT.A', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:20:40', '2022-03-22 15:19:57', '2022-03-22 15:19:57'),
(157, '10000007', 'CUSTODIO GONZALES MAXIMO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:22:26', '2022-03-22 15:19:50', '2022-03-22 15:19:50'),
(158, '10000008', 'DAVILA CABRERA JANETH', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:23:44', '2022-03-22 15:19:43', '2022-03-22 15:19:43'),
(159, '10000009', 'DELGADO CHANAME ARMANDO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:26:56', '2022-03-22 15:19:36', '2022-03-22 15:19:36'),
(160, '10000010', 'DELGADO TANTALEAN FERNANDO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '992956986', 1, NULL, '2022-03-21 11:28:34', '2022-03-22 15:18:55', '2022-03-22 15:18:55'),
(161, '10000011', 'FERNANDEZ DAVILA ALEJANDRO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:29:58', '2022-03-22 15:18:47', '2022-03-22 15:18:47'),
(162, '10000012', 'GONZALES CASTILLO CARLOS A.', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:31:33', '2022-03-22 15:18:38', '2022-03-22 15:18:38'),
(163, '10000013', 'I.E.  J.P.DE CUELLAR  NIVEL PRIMARIO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:32:37', '2022-03-22 15:18:29', '2022-03-22 15:18:29'),
(164, '10000014', 'I.E.  J.P.DE CUELLAR  NIVEL SECUNDARIO', 'Av. Tupac Amaru S/N', '2000-10-09', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:35:07', '2022-03-22 15:18:19', '2022-03-22 15:18:19'),
(165, '10000015', 'LLONTOP AZABACHE SIXTO JORGE', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:36:12', '2022-03-22 15:18:10', '2022-03-22 15:18:10'),
(166, '10000016', 'LLONTOP RODRIGUEZ SUSY janet', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:37:04', '2022-03-22 15:18:02', '2022-03-22 15:18:02'),
(167, '10000017', 'LLUEN HERNANDEZ MANUEL', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:38:08', '2022-03-22 15:17:54', '2022-03-22 15:17:54'),
(168, '10000018', 'MAVILA GIRALDO JAIME', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:39:23', '2022-03-22 15:17:44', '2022-03-22 15:17:44'),
(169, '10000019', 'HIPOLITO MENDOZA ISABEL', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:41:58', '2022-03-22 15:17:37', '2022-03-22 15:17:37'),
(170, '20517668657', 'RD RENTAL S.A.C', 'Av. Tupac Amaru S/N', NULL, 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 2, 'FALTA', '2022-03-21 11:47:30', '2022-03-22 15:17:30', '2022-03-22 15:17:30'),
(171, '10000020', 'RIOJA FERNANDEZ BERNARDINA', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:49:55', '2022-03-22 15:17:24', '2022-03-22 15:17:24'),
(172, '10000021', 'RODRIGUEZ GONZALES CALIXTO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:50:53', '2022-03-22 15:17:17', '2022-03-22 15:17:17'),
(173, '10000022', 'SALDAÑA ANAYA HUGO ARNALDO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 11:52:04', '2022-03-22 15:17:11', '2022-03-22 15:17:11'),
(174, '10000000001', 'SUB CAFAE SE LAMBAYEQUE', 'Av. Tupac Amaru S/N', NULL, 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 2, 'FALTA', '2022-03-21 11:58:22', '2022-03-22 15:17:04', '2022-03-22 15:17:04'),
(175, '10000023', 'ZEA OROS GENARO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '998460121', 1, NULL, '2022-03-21 12:00:45', '2022-03-22 15:16:57', '2022-03-22 15:16:57'),
(176, '10000024', 'COCHERA', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-21 12:08:33', '2022-03-22 15:16:49', '2022-03-22 15:16:49'),
(177, '16559003', 'AGAPITO CAPUÑAY LUCRECIA', 'Av. Tupac Amaru 467 - MZ 10	- LT15', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 16:05:44', '2022-03-22 16:05:44', NULL),
(178, '10000001', 'BURGA DIAZ OLGA YOVANI', 'CAJAMARCA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 16:09:08', '2022-04-25 18:40:41', NULL),
(179, '10000002', 'ALEJANDRIA VILCHEZ WILMA', 'Av. Tupac Amaru S/N - MZ 16 - LT3-B', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 16:13:44', '2022-03-22 16:13:44', NULL),
(180, '10000003', 'AZABACHE ENEQUE LILIANA DEL PILAR', 'Av. Tupac Amaru 439 - MZ 10 - LT 24', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '938303083', 1, NULL, '2022-03-22 16:27:09', '2022-03-28 21:10:04', '2022-03-28 21:10:04'),
(181, '09464810', 'BALLENA CAICEDO WILMER GIOVANI', 'Av. Tupac Amaru  291- MZ 4 - LT B-1', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '949730863', 1, NULL, '2022-03-22 16:36:49', '2022-03-22 16:36:49', NULL),
(182, '10000004', 'BARDALES AREVALO TEDIL', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 16:38:56', '2022-03-22 16:38:56', NULL),
(183, '10000005', 'BARRAGAN GUERRERO ALBERTO', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 16:41:20', '2022-03-22 16:41:20', NULL),
(184, '10000006', 'BURGA PECSEN FIORELLA', 'Av. Tupac Amaru S/N - MZ	14 - LT 3', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 17:01:31', '2022-03-22 17:01:31', NULL),
(185, '40413340', 'CAICEDO ESPINOZA MARTHA', 'Av. Tupac Amaru S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '977540753', 1, NULL, '2022-03-22 17:08:58', '2022-03-22 17:08:58', NULL),
(186, '16595802', 'CAICEDO GONZALES CONSUELO', 'Av. Tupac Amaru 437 - MZ 8 - LT 23', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 17:12:03', '2022-03-22 17:34:35', NULL),
(187, '10000007', 'CAMPOS  TORRES LUCY MARLENY', 'Av. Tupac Amaru 463 - MZ 10 - LT 13', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '971305470', 1, NULL, '2022-03-22 17:16:11', '2022-03-28 21:44:53', '2022-03-28 21:44:53'),
(188, '10000008', 'SILVA  YOVERA  SARA', 'Av. Tupac Amaru  S/N - MZ 10 - LT 10', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 17:20:30', '2022-04-20 23:01:00', '2022-04-20 23:01:00'),
(189, '10000009', 'CORDOVA CERNA DE YALICO CLAUDIA . LT.A', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '974485884', 1, NULL, '2022-03-22 17:48:07', '2022-03-23 23:47:17', '2022-03-23 23:47:17'),
(190, '10000010', 'CORDOVA CERNA DE YALICO CLAUDIA. LT.B', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 17:52:35', '2022-03-24 15:32:57', '2022-03-24 15:32:57'),
(191, '16675067', 'CORDOVA CORDOVA  JOSE', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 17:55:31', '2022-03-22 17:55:31', NULL),
(192, '10000011', 'CORONEL CASTILLO CLARIZA', 'Av. Tupac Amaru  S/N - Mz M - LT	8', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 17:59:08', '2022-03-22 17:59:08', NULL),
(193, '10000012', 'COTRINA ORTIZ OSCAR', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 18:01:32', '2022-03-24 15:30:26', NULL),
(194, '10000013', 'COTRINA ORTIZ OSCAR  LT.B', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 18:07:23', '2022-03-24 15:30:04', '2022-03-24 15:30:04'),
(195, '10000014', 'ESPINOZA NICOLAS RAYMUNDO', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 18:12:32', '2022-04-20 23:47:54', NULL),
(196, '10000015', 'CUSTODIO GONZALES MAXIMO', 'Av. Tupac Amaru  S/N', '2000-10-10', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 18:28:48', '2022-03-22 18:28:48', NULL),
(197, '10000016', 'GONZALES VILLEGAS LUIS  ORLANDO', 'Av. Tupac Amaru  S/N - MZ 14', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 18:31:58', '2022-04-20 23:28:02', '2022-04-20 23:28:02'),
(198, '10000017', 'DAVILA CABRERA JANETH', 'Av. Tupac Amaru  S/N - MZ 14 - LT 1', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 18:36:32', '2022-03-22 18:36:32', NULL),
(199, '10000018', 'DELGADO CHANAME ARMANDO', 'Av. Tupac Amaru  S/N - MZ 4 - LT 4', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 18:43:50', '2022-03-22 18:43:50', NULL),
(200, '10000019', 'DELGADO TANTALEAN FERNANDO', 'Av. Tupac Amaru  S/N - MZ 4 - LT 3', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '992956986', 1, NULL, '2022-03-22 18:52:34', '2022-03-22 18:52:34', NULL),
(201, '10000020', 'VIPETROS S.A.C', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 19:03:27', '2022-03-28 22:19:39', '2022-03-28 22:19:39'),
(202, '16760171', 'ENEQUE GONZALES LORENZA', 'Av. Tupac Amaru  331 - MZ 6 - LT 17', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 19:12:56', '2022-03-22 19:12:56', NULL),
(203, '10000021', 'ESPINOZA NICOLAS JULIO “B”', 'Av. Tupac Amaru  217', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 19:26:34', '2022-03-24 15:26:03', '2022-03-24 15:26:03'),
(204, '16595851', 'FARRO YOVERA GREGORIA', 'Av. Tupac Amaru  337 - MZ 6 - LT 20-A', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 19:31:06', '2022-03-22 19:31:06', NULL),
(205, '10000024', 'FERNANDEZ DAVILA ALEJANDRO', 'Av. Tupac Amaru  S/N - MZ 4 - LT B-2', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 19:38:46', '2022-04-18 00:00:44', NULL),
(206, '16637024', 'FLORES UYPAN CARMEN', 'Av. Tupac Amaru  335 - MZ 6 - LT 19', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 19:51:21', '2022-04-22 18:05:17', NULL),
(207, '16679634', 'FUENTES ESPINOZA ANGELITA', 'Av. Tupac Amaru  323 - MZ 6 - LT 13', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 19:55:03', '2022-03-22 19:55:03', NULL),
(208, '16778018', 'GAMARRA GUZMAN MANUELA', 'Av. Tupac Amaru  343 - MZ 6 - LT 23', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 19:59:14', '2022-03-22 19:59:14', NULL),
(209, '10000025', 'GARNIQUE BALLENA ANDRES:AGRIPINA MECHAN', 'Av. Tupac Amaru 349 - MZ 6 - LT 26', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 20:21:32', '2022-03-24 15:22:27', '2022-03-24 15:22:27'),
(210, '16463804', 'GAVIDIA ARRASCUE EMILIO WENCESLAO', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 20:25:09', '2022-03-22 20:25:09', NULL),
(211, '10000026', 'GONZALES CASTILLO CARLOS ALBERTO', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 20:28:46', '2022-03-24 15:19:52', NULL),
(212, '10000027', 'GONZALES CHUMIOQUE JOSE L.', 'Av. Tupac Amaru  417 - MZ 8 - LT 12-A', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 20:32:57', '2022-03-28 22:52:25', '2022-03-28 22:52:25'),
(213, '16637009', 'GONZALES PUICAN CAYETANO', 'Av. Tupac Amaru  485 - MZ 12 - LT 6', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '950909219', 1, NULL, '2022-03-22 20:50:09', '2022-03-22 20:50:09', NULL);
INSERT INTO `CLIENTE` (`CLI_CODIGO`, `CLI_DOCUMENTO`, `CLI_NOMBRES`, `CLI_DIRECCION`, `CLI_FECHA_NAC`, `CLI_DISTRITO`, `CLI_PROVINCIA`, `CLI_DEPARTAMENTO`, `CLI_EMAIL`, `CLI_TELEFONO`, `CLI_TIPO`, `CLI_REPRES_LEGAL`, `CLI_CREATED`, `CLI_UPDATED`, `CLI_DELETED`) VALUES
(214, '10000028', 'I.E JAVIER PEREZ DE CUELLAR  NIVEL PRIMARIO', 'Av. Tupac Amaru  S/N - MZ 3 - LT 5  CHOSICA DEL NORTE', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 22:49:10', '2022-04-25 22:18:16', NULL),
(215, '10000029', 'DESHABITADO', 'Av. Tupac Amaru S/N - MZ 8 - LT 5', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 22:52:10', '2022-04-20 23:07:28', '2022-04-20 23:07:28'),
(216, '10000030', 'SILVA RUIZ WILLiAM', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 23:10:36', '2022-04-21 00:19:11', NULL),
(217, '10000031', 'I.E JAVIER PEREZ DE CUELLAR  NIVEL SECUNDARIO', 'Av. Tupac Amaru  341 - MZ 4 - LT 8', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 23:12:41', '2022-04-25 22:17:38', NULL),
(218, '10000032', 'LLONTOP AZABACHE SIXTO JORGE', 'Av. Tupac Amaru  S/N - MZ 8 -LT 21-A', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 23:40:08', '2022-03-22 23:40:08', NULL),
(219, '10000033', 'LLONTOP ENEQUE JUANA ROSA', 'Av. Tupac Amaru  469 - MZ 10 - LT 16', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-22 23:44:03', '2022-03-29 00:59:22', '2022-03-29 00:59:22'),
(220, '16420364', 'SALAZAR PUYEN MARIA ANABELVA', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:01:13', '2022-04-21 00:00:02', NULL),
(221, '80512062', 'LLONTOP MIO CESAR AUGUSTO', 'Av. Tupac Amaru 489 - MZ 12 - LT 8', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:08:04', '2022-03-23 00:08:04', NULL),
(222, '10000034', 'LLONTOP RODRIGUEZ SUSY JANET', 'Av. Tupac Amaru  S/N - MZ 10 - LT 17-A', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:18:33', '2022-03-23 00:19:16', NULL),
(223, '16595888', 'LLUEN DE COLCHON EUDOCIA', 'Av. Tupac Amaru  S/N - MZ 12 - LT 2', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:23:00', '2022-03-23 00:23:00', NULL),
(224, '16787429', 'LLUEN DE LA OLIVA JANETH', 'Av. Tupac Amaru 339 - MZ 6 - LT 21', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:27:47', '2022-03-23 00:27:47', NULL),
(225, '10000035', 'LLUEN HERNANDEZ MANUEL', 'Av. Tupac Amaru  357 - MZ 8 - LT 2', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:35:10', '2022-03-23 00:35:10', NULL),
(226, '10000036', 'MAVILA GIRALDO JAIME', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:37:26', '2022-03-23 00:37:26', NULL),
(227, '10000037', 'MECHAN AGAPITO CESAR AUGUSTO', 'Av. Tupac Amaru  351 - MZ 6 - LT 27', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '925234216', 1, NULL, '2022-03-23 00:41:09', '2022-03-28 20:38:40', '2022-03-28 20:38:40'),
(228, '16596557', 'MECHAN CHAVESTA MANUEL', 'Av. Tupac Amaru  525 - MZ 16 - LT 3-A', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:47:10', '2022-03-29 03:37:32', '2022-03-29 03:37:32'),
(229, '10000038', 'MECHAN GONZALES MANUEL DE LA CRUZ', 'Av. Tupac Amaru  353 - MZ 6 - LT 28', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 00:56:25', '2022-03-23 00:56:25', NULL),
(230, '18061514', 'MENDOZA FLORES MERCEDES', 'Av. Tupac Amaru  S/N - Mz C - LT 2', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 01:13:03', '2022-03-23 01:13:03', NULL),
(231, '10000039', 'HIPOLITO MENDOZA ISABEL', 'Av. Tupac Amaru S/N - MZ 8 - LT 16', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 01:17:33', '2022-03-23 01:17:33', NULL),
(232, '16598360', 'MISCAN LLUEN FLORENCIO', 'Av. Tupac Amaru  465 - MZ 10 - LT 12', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '980074710', 1, NULL, '2022-03-23 01:41:52', '2022-03-23 01:41:52', NULL),
(233, '16721156', 'NAVARRO COLOMA SARA DE LOS MILAGROS', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 01:58:47', '2022-03-23 01:58:47', NULL),
(234, '27978094', 'NUÑEZ SANCHEZ MOISES', 'Av. Tupac Amaru  S/N - MZ 1 - LT 3', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 02:02:43', '2022-03-23 02:02:43', NULL),
(235, '43785314', 'PEÑA PEÑA ANGELA', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 02:18:29', '2022-03-23 02:18:29', NULL),
(236, '09227006', 'RODAS CASTAÑEDA ANANIAS', 'Av. Tupac Amaru  523 - MZ 12 - LT 5', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 02:31:46', '2022-03-23 02:31:46', NULL),
(237, '16527270', 'QUISPE SALVADOR ELENA', 'Av. Tupac Amaru  359 MZ 8 - LT 3', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 02:44:35', '2022-03-24 15:13:41', NULL),
(238, '16594495', 'REQUE VELASQUEZ VICTOR', 'Av. Tupac Amaru  411 - MZ  8 - LT 9', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 02:55:57', '2022-03-23 02:55:57', NULL),
(239, '10000040', 'RIOJA FERNANDEZ BERNARDINA', 'Av. Tupac Amaru  523 - MZ 16 - LT 3', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 03:09:49', '2022-03-23 03:09:49', NULL),
(240, '10000041', 'RODRIGUEZ GONZALES CALIXTO', 'Av. Tupac Amaru  529 - MZ 16 - LT 3-C', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 03:22:40', '2022-03-23 03:22:40', NULL),
(241, '80402072', 'SALAZAR CHAVESTA MARTIN', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 03:47:06', '2022-03-23 03:47:06', NULL),
(242, '16766467', 'SALAZAR CHAVESTA PABLO', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 03:50:14', '2022-03-23 03:50:14', NULL),
(243, '17595663', 'SALAZAR CHAVESTA SEBASTIAN', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 03:52:37', '2022-03-23 03:52:37', NULL),
(244, '10000042', 'SALDAÑA ANAYA HUGO ARNALDO', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 03:57:36', '2022-03-23 03:57:36', NULL),
(245, '16448618', 'SECLEN NUÑEZ CRONWELL', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 04:02:15', '2022-03-23 04:02:15', NULL),
(246, '16470526', 'SOSA URCIA ROSAURA', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 04:03:59', '2022-03-23 04:03:59', NULL),
(247, '18133395', 'SUAREZ DE MATOS ZOILA ISABEL', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 04:10:00', '2022-03-23 04:10:00', NULL),
(248, '10000044', 'SUB CAFAE SE LAMBAYEQUE', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 04:13:23', '2022-03-23 04:13:23', NULL),
(249, '10000045', 'MORALES FLORES CANDELARIA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 04:28:33', '2022-04-21 00:12:11', NULL),
(250, '16597282', 'VELASQUEZ GARCIA TERESA', 'Av. Tupac Amaru  S/N - MZ 8 - LT 17-A', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 04:47:20', '2022-03-23 04:47:20', NULL),
(251, '10000047', 'VELASQUEZ OLIDEN WALTER RICHARD B', 'Av. Tupac Amaru  413 - MZ 10 - LT 7B', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '963636907', 1, NULL, '2022-03-23 04:56:14', '2022-03-23 05:03:33', '2022-03-23 05:03:33'),
(252, '10000046', 'VELASQUEZ OLIDEN WALTER RICHARD C', 'Av. Tupac Amaru  413 - MZ 10 - LT 7C', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 05:10:31', '2022-03-24 15:10:15', '2022-03-24 15:10:15'),
(253, '10000047', 'VELASQUEZ OLIDEN CESAR D', 'Av. Tupac Amaru  413 - MZ 10 - LT 7D', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 05:15:44', '2022-03-24 15:06:19', '2022-03-24 15:06:19'),
(254, '16459292', 'WONG DE CHONG BLANCA ALEJANDRINA', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 05:30:28', '2022-04-25 23:33:15', NULL),
(255, '16699040', 'YALICO VELASQUEZ EDGARD', 'Av. Tupac Amaru  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 05:32:05', '2022-03-23 05:32:05', NULL),
(256, '10000048', 'ZEA OROS GENARO', 'Av. Tupac Amaru  S/N - MZ 18 - LT 1', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '998460121', 1, NULL, '2022-03-23 05:39:21', '2022-03-23 05:39:21', NULL),
(257, '01111111', 'ALVAREZ CUBAS DE ESPINOZA KARLA', 'A.v. CESAR VALLEJO 345', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 20:16:52', '2022-03-29 22:46:38', '2022-03-29 22:46:38'),
(258, '03333333', 'AZABACHE SENMACHE IRIS JENNY', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 20:29:45', '2022-03-29 22:47:17', '2022-03-29 22:47:17'),
(259, '04444444', 'BURGA MENDOZA CATALINO', 'A.v. CESAR VALLEJO 259', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '962883382', 1, NULL, '2022-03-23 20:31:52', '2022-03-29 22:47:42', '2022-03-29 22:47:42'),
(260, '05555555', 'BENAVIDEZ RODRIGUEZ VICTOR', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 20:37:04', '2022-03-29 22:48:06', '2022-03-29 22:48:06'),
(261, '06666666', 'CASTRO ALVARADO VALENTIN', 'AV. CESAR VALLEJO S/N - MZ 5 - LT 14', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-23 20:51:41', '2022-03-29 22:48:26', '2022-03-29 22:48:26'),
(262, '07777777', 'CHISCUL IGNACIO ZAIDA CRUZ', 'AV . CESAR VALLEJO 156', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '916109920', 1, NULL, '2022-03-23 21:05:58', '2022-03-23 21:05:58', NULL),
(263, '11111111', 'ALVAREZ CUBAS DE ESPINOZA KARLA', 'A.v. CESAR VALLEJO 345 - DPTO . B', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 10:34:34', '2022-03-30 10:34:34', NULL),
(264, '16593659', 'AYASTA AGAPITO RAFAEL', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 10:39:57', '2022-03-30 10:39:57', NULL),
(265, '16598103', 'AZABACHE LLUEN JULIO', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 10:47:31', '2022-04-26 00:09:24', '2022-04-26 00:09:24'),
(266, '22222222', 'AZABACHE LLUEN JULIO', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 10:48:21', '2022-03-30 10:48:21', NULL),
(267, '33333333', 'AZABACHE SENMACHE IRIS JENNY', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 10:58:29', '2022-03-30 10:58:29', NULL),
(268, '44444444', 'BURGA MENDOZA CATALINO', 'A.v. CESAR VALLEJO 259', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '962883382', 1, NULL, '2022-03-30 11:02:19', '2022-03-30 11:02:19', NULL),
(269, '46284027', 'BUSTAMANTE ASCENCIO LUIS BENJAMIN', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 11:10:36', '2022-03-30 11:10:36', NULL),
(270, '55555555', 'BENAVIDEZ RODRIGUEZ VICTOR', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 11:15:20', '2022-03-30 11:15:20', NULL),
(271, '43223493', 'CARHUATANTA DE GAMARRA ELIZAB', 'A.v. CESAR VALLEJO 127', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979039985', 1, NULL, '2022-03-30 11:18:29', '2022-03-30 11:18:29', NULL),
(272, '66666666', 'CASTRO ALVARADO VALENTIN', 'A.v. CESAR VALLEJO S/N - MZ 5 - LT 14', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 11:24:06', '2022-03-30 11:24:06', NULL),
(273, '16594469', 'CAVERO VDA. DE IGNACIO RITA VIOLETA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 11:30:07', '2022-03-30 11:30:07', NULL),
(274, '41014700', 'CENTURION TANTALEAN JUSTO', 'A.v. CESAR VALLEJO 269 - LT B', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 11:34:15', '2022-03-30 11:34:15', NULL),
(275, '16691889', 'CENTURION TANTALEAN SEGUNDO', 'A.v. CESAR VALLEJO 269 - LT. C', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 11:38:45', '2022-03-30 11:38:45', NULL),
(276, '77777777', 'CENTURION TANTALEAN MARIA ROSA', 'A.v. CESAR VALLEJO 269 - LT. A', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '916109920', 1, NULL, '2022-03-30 11:45:18', '2022-04-26 00:37:34', NULL),
(277, '88888888', 'CONGREGACION TESTIGOS DE JEHOVA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 11:49:14', '2022-03-30 11:49:14', NULL),
(278, '99999999', 'CONGREGACION EVANGELICA NAZARENO', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 11:54:40', '2022-03-30 11:54:40', NULL),
(279, '16596787', 'CORNEJO DE AZABACHE JUANA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:00:21', '2022-03-30 12:00:21', NULL),
(280, '10101010', 'CORRALES FARRO DAMIAN', 'A.v. CESAR VALLEJO 319', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:06:46', '2022-03-30 12:06:46', NULL),
(281, '16593628', 'CUBAS ROJAS ANDREA', 'A.v. CESAR VALLEJO 345 - LT. A', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '970982767', 1, NULL, '2022-03-30 12:11:48', '2022-03-30 12:11:48', NULL),
(282, '16594537', 'DIAZ  SEGOVIA HILARIO', 'A.v. CESAR VALLEJO 125', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:15:49', '2022-03-30 12:15:49', NULL),
(283, '16595817', 'DIAZ BENAVIDEZ AMADO', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:20:04', '2022-03-30 12:20:04', NULL),
(284, '12121212', 'DIAZ CUEVA MARINA ELIZABETH', 'A.v. CESAR VALLEJO 140', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:25:40', '2022-03-30 12:25:40', NULL),
(285, '16644053', 'DIAZ VILLEGAS ARISTIDES', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:31:57', '2022-03-30 12:31:57', NULL),
(286, '16595557', 'DIAZ VILLEGAS HELMER', 'A.v. CESAR VALLEJO 350', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:37:25', '2022-03-30 12:37:25', NULL),
(287, '16474684', 'DIAZ VILLEGAS JULIO', 'A.v. CESAR VALLEJO 246', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:41:51', '2022-03-30 12:41:51', NULL),
(288, '13131313', 'DURAND MARCO NELLY', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:47:02', '2022-03-30 12:47:02', NULL),
(289, '16552354', 'EFFIO DE GAMARRA MARTINA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 12:49:58', '2022-03-30 12:49:58', NULL),
(290, '16717189', 'FARRO AYASTA MANUEL', 'A.v. CESAR VALLEJO S/N - MZ 9 - LT 5', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '978664940', 1, NULL, '2022-03-30 12:54:48', '2022-03-30 12:54:48', NULL),
(291, '80268836', 'FARRO LLONTOP FELIZA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 13:31:52', '2022-03-30 13:31:52', NULL),
(292, '16595810', 'FERNANDEZ RIOJA EUSEBIO', 'A.v. CESAR VALLEJO 390', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '994096806', 1, NULL, '2022-03-30 13:36:52', '2022-03-30 13:36:52', NULL),
(293, '40194169', 'FERNANDEZ RIOJA JULIANA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 13:41:01', '2022-03-30 13:41:01', NULL),
(294, '14141414', 'FLORES PEREZ IRMA GLADYS', 'A.v. CESAR VALLEJO S/N - MZ A - LT 8', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 13:46:29', '2022-03-30 13:46:29', NULL),
(295, '16766424', 'GAMARRA AZABACHE SANTOS', 'A.v. CESAR VALLEJO 164', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 13:51:21', '2022-03-30 13:51:21', NULL),
(296, '16594463', 'GONZALES ESPINOZA MANUEL', 'A.v. CESAR VALLEJO 206', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 13:58:30', '2022-03-30 13:58:30', NULL),
(297, '41369358', 'GONZALES GUZMAN PEDRO', 'A.v. CESAR VALLEJO 394', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 14:03:13', '2022-03-30 14:03:13', NULL),
(298, '16551332', 'GONZALES GUZMAN VICTORIA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 14:06:31', '2022-03-30 14:06:31', NULL),
(299, '16773299', 'GONZALES RAMIREZ MARIA ELENA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979830154', 1, NULL, '2022-03-30 14:12:40', '2022-03-30 14:12:40', NULL),
(300, '16595840', 'GONZALES RAMIREZ PABLO', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 14:16:58', '2022-03-30 14:16:58', NULL),
(301, '43731386', 'GONZALES RODRIGUEZ IVAN', 'A.v. CESAR VALLEJO S/N - MZ 9 - LT 3', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '921339676', 1, NULL, '2022-03-30 14:20:30', '2022-03-30 14:20:30', NULL),
(302, '16594540', 'GONZALES RODRIGUEZ SANTOS', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 14:24:13', '2022-03-30 14:24:13', NULL),
(303, '16594579', 'GONZALES VALENCIA JOSE', 'A.v. CESAR VALLEJO 273', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 14:39:05', '2022-03-30 14:39:05', NULL),
(304, '80268581', 'DIAZ CUEVA LUISA CRISTINA', 'A.v. CESAR VALLEJO 318', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '918857681', 1, NULL, '2022-03-30 14:45:03', '2022-03-30 14:45:03', NULL),
(305, '16636137', 'GONZALES VELASQUEZ ELVIRA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 14:50:03', '2022-03-30 14:50:03', NULL),
(306, '15151515', 'GUERRERO CABRERA MIRIAN ELVIRA', 'A.v. CESAR VALLEJO 372', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 14:56:55', '2022-04-26 01:40:23', NULL),
(307, '16594587', 'GUTIERREZ TELLO JUAN', 'A.v. CESAR VALLEJO 113', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 14:59:52', '2022-03-30 15:01:41', NULL),
(308, '16594485', 'GUZMAN AGAPITO MAXIMO', 'A.v. CESAR VALLEJO 307', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979501183', 1, NULL, '2022-03-30 15:07:29', '2022-03-30 15:07:29', NULL),
(309, '16161616', 'GUZMAN IGNACIO EMANUEL', 'A.v. CESAR VALLEJO #156 - MZ 5 - LT	7 B', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 15:11:46', '2022-03-30 15:11:46', NULL),
(310, '16429528', 'GUZMAN SAMILLAN ANDRES', 'A.v. CESAR VALLEJO S/N - MZ 5 - LT 18', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 15:15:59', '2022-03-30 15:15:59', NULL),
(311, '80381374', 'GONZALES COLCHON SEGUNDO AURELIO', 'AV.CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '991866161', 1, NULL, '2022-03-30 15:20:09', '2022-03-30 15:20:09', NULL),
(312, '16615628', 'GUZMAN SAMILLAN JUANA', 'A.v. CESAR VALLEJO S/N - MZ 5 - LT 15', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '920266458', 1, NULL, '2022-03-30 15:25:41', '2022-03-30 15:25:41', NULL),
(313, '16594589', 'HERRERA ASCURRA DOMINGO', 'A.v. CESAR VALLEJO 124', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 15:35:44', '2022-03-30 15:35:44', NULL),
(314, '16430962', 'HORNA FARROÑAY JOSE', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 15:39:17', '2022-03-30 15:39:17', NULL),
(315, '16595807', 'HORNA FARROÑAY VIOLETA', 'A.v. CESAR VALLEJO 148 - MZ 5 - LT 10', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 15:43:10', '2022-03-30 15:43:10', NULL),
(316, '16960453', 'HUACCHA CHAVEZ ELVIRA', 'A.v. CESAR VALLEJO 207', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '910813053', 1, NULL, '2022-03-30 15:47:27', '2022-03-30 15:47:27', NULL),
(317, '17611765', 'HUAMANI MENDOZA FAUSTO', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 15:51:15', '2022-03-30 15:51:15', NULL),
(318, '48790425', 'HERMIDA PEÑA CARLOS ANDRES', 'A.v. CESAR VALLEJO 127 - LT 7', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 15:56:55', '2022-03-30 15:56:55', NULL),
(319, '16595837', 'IGNACIO CAVERO MERCEDES', 'A.v. CESAR VALLEJO 141', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '941941672', 1, NULL, '2022-03-30 16:03:47', '2022-03-30 16:03:47', NULL),
(320, '16594431', 'IGNACIO DE ACUÑA  ENRIQUETA', 'A.v. CESAR VALLEJO 157', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 16:08:31', '2022-03-30 16:08:31', NULL),
(321, '17171717', 'LLANOS TORO EMILIO', 'A.v. CESAR VALLEJO 226', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 16:12:30', '2022-03-30 16:12:30', NULL),
(322, '18181818', 'LLONTOP ARRASCO FRANCISCA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 16:19:39', '2022-03-30 16:19:39', NULL),
(323, '19191919', 'LLONTOP ATENCIO GIORGIO', 'A.v. CESAR VALLEJO S/N - MZ  C - LT 15', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 16:29:47', '2022-03-30 16:29:47', NULL),
(324, '16594480', 'LLUEN GUZMAN EMILIANA', 'A.v. CESAR VALLEJO S/N - MZ 21 - LT 15', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '950591688', 1, NULL, '2022-03-30 16:37:05', '2022-03-30 16:37:05', NULL),
(325, '16594536', 'LLUEN GUZMAN ISIDRO', 'A.v. CESAR VALLEJO 354', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 16:41:58', '2022-03-30 16:41:58', NULL),
(326, '16691878', 'LLUEN PISFIL SANTOS', 'A.v. CESAR VALLEJO 270', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '979868900', 1, NULL, '2022-03-30 16:48:25', '2022-03-30 16:48:25', NULL),
(327, '20202020', 'LLUEN PISFIL SILVIA', 'A.v. CESAR VALLEJO # 313 - LT B', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 16:53:20', '2022-04-21 01:16:12', '2022-04-21 01:16:12'),
(328, '16594405', 'LLUEN PISFIL VICTOR', 'A.v. CESAR VALLEJO 300', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '912790285', 1, NULL, '2022-03-30 16:58:46', '2022-03-30 16:58:46', NULL),
(329, '16594428', 'MARCELO YAUCE MARINA', 'A.v. CESAR VALLEJO 250', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 17:03:17', '2022-03-30 17:03:17', NULL),
(330, '21212121', 'MECHAN GONZALES WILMER', 'A.v. CESAR VALLEJO 207', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 17:08:14', '2022-03-30 17:08:14', NULL),
(331, '16666204', 'MECHAN TULLUME VICENTE', 'A.v. CESAR VALLEJO 154', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 17:11:42', '2022-03-30 17:11:42', NULL),
(332, '16783656', 'MEOÑO TORO LUIS ALBERTO', 'A.v. CESAR VALLEJO 261', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 17:16:03', '2022-03-30 17:16:03', NULL),
(333, '16597234', 'MIÑOPE GONZALES CALIXTO', 'A.v. CESAR VALLEJO 257', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 17:18:28', '2022-03-30 17:18:28', NULL),
(334, '23232323', 'LLONTOP GUZMAN MANUELA', 'A.v. CESAR VALLEJO 157', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 17:24:29', '2022-03-30 17:24:29', NULL),
(335, '16598204', 'MIRANDA DE GONZALES CARMEN', 'A.v. CESAR VALLEJO 210', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-30 17:29:55', '2022-03-30 17:29:55', NULL),
(336, '24242424', 'MIRES FLORES MILAGROS', 'A.v. CESAR VALLEJO 159', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 09:41:06', '2022-03-31 09:41:06', NULL),
(337, '16628174', 'OBANDO CAPUÑAY BLANCA', 'A.v. CESAR VALLEJO 202', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '978246099', 1, NULL, '2022-03-31 09:49:19', '2022-03-31 09:49:19', NULL),
(338, '16595578', 'OJEDA PEÑA ELENA', 'A.v. CESAR VALLEJO 161', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 09:54:08', '2022-03-31 09:54:08', NULL),
(339, '27702656', 'PAQUIRACHIN RAMOS REYES', 'A.v. CESAR VALLEJO 162', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 10:02:15', '2022-03-31 10:02:15', NULL),
(340, '25252525', 'PEÑA DELGADO CARLOS', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 10:07:06', '2022-03-31 10:07:06', NULL),
(341, '26262626', 'PISFIL CHANCAFE AGUSTIN', 'A.v. CESAR VALLEJO 313', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 10:16:30', '2022-03-31 10:16:30', NULL),
(342, '27272727', 'CENTRO DE SALUD CHOSICA DEL NORTE', 'A.v. CESAR VALLEJO 165', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 10:28:02', '2022-03-31 10:28:02', NULL),
(343, '16594406', 'PURIZACA CHUNGA RUPERTO', 'A.v. CESAR VALLEJO 166', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 10:38:41', '2022-03-31 10:38:41', NULL),
(344, '28282828', 'QUINTANA BERRIOS OLGA', 'A.v. CESAR VALLEJO 167', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 10:45:13', '2022-03-31 10:45:13', NULL),
(345, '16433550', 'REQUE CASTILLO ELENA', 'A.v. CESAR VALLEJO 378', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 10:52:07', '2022-03-31 10:52:07', NULL),
(346, '16596407', 'RODRIGUEZ VARGAS CARLOS', 'A.v. CESAR VALLEJO 239', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '940344914', 1, NULL, '2022-03-31 10:55:04', '2022-03-31 10:55:04', NULL),
(347, '16593626', 'RODRIGUEZ VARGAS HUGO', 'A.v. CESAR VALLEJO 249', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '951484196', 1, NULL, '2022-03-31 10:58:35', '2022-03-31 10:58:35', NULL),
(348, '16728513', 'RODRIGUEZ VARGAS SEGUNDO', 'A.v. CESAR VALLEJO 171', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:03:36', '2022-03-31 11:03:36', NULL),
(349, '29292929', 'SALAZAR PUYEN OSCAR MATIAS', 'A.v. CESAR VALLEJO 366', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '981984648', 1, NULL, '2022-03-31 11:07:08', '2022-03-31 11:07:08', NULL),
(350, '30303030', 'SANCHEZ CUEVA SANTOS', 'A.v. CESAR VALLEJO 242', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:11:39', '2022-03-31 11:11:39', NULL),
(351, '16595900', 'SANCHEZ PARDO BETTY', 'A.v. CESAR VALLEJO 174', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:14:40', '2022-03-31 11:14:40', NULL),
(352, '16637115', 'SENMACHE GONZALES JORGE', 'A.v. CESAR VALLEJO 207', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:18:05', '2022-03-31 11:18:05', NULL),
(353, '16551688', 'SENMACHE GONZALES ROSA', 'A.v. CESAR VALLEJO 207', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:24:45', '2022-03-31 11:24:45', NULL),
(354, '16636876', 'SENMACHE GONZALES WALTER', 'A.v. CESAR VALLEJO 207', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:27:49', '2022-03-31 11:27:49', NULL),
(355, '16559317', 'SENMACHE GONZALES YOLANDA', 'A.v. CESAR VALLEJO 180', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:30:04', '2022-03-31 11:30:04', NULL),
(356, '40800881', 'TANTALEAN HURTADO EFRAIN', 'A.v. CESAR VALLEJO 181', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:36:07', '2022-03-31 11:36:07', NULL),
(357, '16799165', 'TUCUNANGO FERNANDEZ DAVID', 'A.v. CESAR VALLEJO 183', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:39:14', '2022-03-31 11:39:14', NULL),
(358, '31313131', 'RODRIGUEZ MECHAN ALEJANDRO', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:45:28', '2022-04-07 13:09:17', NULL),
(359, '16691084', 'VELASQUEZ GARCIA AURORA', 'A.v. CESAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:48:50', '2022-03-31 11:48:50', NULL),
(360, '28063711', 'VELEZMORO VARGAS WILSON', 'A.v. CESAR VALLEJO 238', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '965675779', 1, NULL, '2022-03-31 11:51:42', '2022-03-31 11:51:42', NULL),
(361, '32323232', 'YMAN PEREZ MERLY LILIANA', 'A.v. CESAR VALLEJO 187', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:53:53', '2022-03-31 11:53:53', NULL),
(362, '16525518', 'YOVERA GONZALES RICARDO', 'A.v. CESAR VALLEJO 384 - MZ 9 - LT 15', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 11:56:24', '2022-03-31 11:56:24', NULL),
(363, '34343434', 'BARDALES DIAZ ESPERANZA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 12:13:14', '2022-03-31 12:13:14', NULL),
(364, '27364605', 'BURGA CIEZA JESUS', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '958518921', 1, NULL, '2022-03-31 12:21:29', '2022-03-31 12:21:29', NULL),
(365, '35353535', 'CHAVEZ VERA JOSE EUDEN', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 12:25:38', '2022-03-31 12:25:38', NULL),
(366, '36363636', 'CHUQUIRUNA VASQUEZ FILONILA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 12:42:55', '2022-03-31 12:42:55', NULL),
(367, '37373737', 'CHUQUIRUNA VASQUEZ ROSALIA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 12:48:13', '2022-03-31 12:48:13', NULL),
(368, '38383838', 'COBEÑAS SILVA CARMEN JUANA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 12:51:25', '2022-03-31 12:51:25', NULL),
(369, '39393939', 'CUBAS VASQUEZ SEGUNDO', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 12:59:20', '2022-03-31 12:59:20', NULL),
(370, '40404040', 'EFFIO OBANDO BLANCA CIVILA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 13:42:05', '2022-03-31 13:45:11', NULL),
(371, '41414141', 'CUSTODIO GUZMAN ARTEMIO', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 13:54:16', '2022-03-31 14:14:06', NULL),
(372, '42424242', 'CUBAS PERALTA SONIA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 13:57:31', '2022-03-31 13:57:31', NULL),
(373, '43434343', 'GAMARRA EFFIO TERESA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 14:00:46', '2022-03-31 14:00:46', NULL),
(374, '45454545', 'GOMEZ CUEVA JESUS LEONOR', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 14:04:45', '2022-03-31 14:04:45', NULL),
(375, '46464646', 'GUEVARA LOZADA FLORMIRA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 14:36:12', '2022-03-31 14:36:12', NULL),
(376, '47474747', 'GUZMAN CHAVESTA TEODORA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 14:46:09', '2022-03-31 14:46:09', NULL),
(377, '48484848', 'GUZMAN FARROÑAY ZENOBIA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 14:50:16', '2022-03-31 14:50:16', NULL),
(378, '49494949', 'IGLESIA MOVIMIENTO MISIONERO MUNDIAL CHOSICA DEL NORTE', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 14:55:46', '2022-03-31 14:55:46', NULL),
(379, '50505050', 'JULCA ALBERCA EMILIA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 14:59:10', '2022-03-31 14:59:10', NULL),
(380, '51515151', 'LLONTOP LLONTOP  VICTOR', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:02:58', '2022-03-31 15:02:58', NULL),
(381, '52525252', 'MESIA SANCHEZ SIDALIA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:06:31', '2022-03-31 15:06:31', NULL),
(382, '53535353', 'MORALES ALCANTAR ANGELICA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:13:23', '2022-03-31 15:13:23', NULL),
(383, '54545454', 'PEREZ SAAVEDRA VICTOR ALFONSO', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:17:37', '2022-03-31 15:17:37', NULL),
(384, '56565656', 'PUICON GAMARRA NICOLAS', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:22:09', '2022-03-31 15:22:09', NULL),
(385, '57575757', 'RAMOS CHERO RAFAEL', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:25:39', '2022-03-31 15:25:39', NULL),
(386, '58585858', 'RAMOS CHERO VENICIO', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:29:07', '2022-03-31 15:29:07', NULL),
(387, '59595959', 'RAMOS ESPINOZA FRAY MIGUEL', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:34:53', '2022-03-31 15:34:53', NULL),
(388, '60606060', 'ROJAS DELGADO JOSE MANUEL', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:38:09', '2022-03-31 15:38:09', NULL),
(389, '61616161', 'RUIZ ANGULO NELLY', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:40:57', '2022-03-31 15:40:57', NULL),
(390, '62626262', 'SALAZAR AGAPITO JAIME', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:43:44', '2022-03-31 15:43:44', NULL),
(391, '63636363', 'SALAZAR RODRIGUEZ JOSE', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:47:16', '2022-03-31 15:47:16', NULL),
(392, '64646464', 'SANCHEZ RAMOS MABEL', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 15:58:18', '2022-03-31 15:58:18', NULL),
(393, '65656565', 'VELASQUEZ LLUEN GUILLIANA', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:04:49', '2022-03-31 16:04:49', NULL),
(394, '67676767', 'VELASQUEZ MORALES ARMANDO', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:07:39', '2022-03-31 16:07:39', NULL),
(395, '68686868', 'VELASQUEZ RODRIGUEZ PEDRO', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:15:06', '2022-03-31 16:15:06', NULL),
(396, '69696969', 'ZURITA ROJAS LORGIN', 'PROLOG.CÉSAR VALLEJO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:17:28', '2022-03-31 16:17:28', NULL),
(397, '70707070', 'ACOSTA CHAVESTA JUANA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:28:31', '2022-03-31 16:28:31', NULL),
(398, '71717171', 'ACOSTA CHAVESTA CARLOS', 'SAN LUIS S/N', '2000-12-10', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:38:57', '2022-03-31 16:38:57', NULL),
(399, '72727272', 'ACOSTA CHAVESTA JACINTA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:41:39', '2022-03-31 16:41:39', NULL),
(400, '73737373', 'ACOSTA CHAVESTA MANUEL', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:48:30', '2022-03-31 16:48:30', NULL),
(401, '74747474', 'AGAPITO RODRIGUEZ MARIA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:53:46', '2022-03-31 16:53:46', NULL),
(402, '75757575', 'ANGELES SARMIENTO VICTOR', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 16:56:39', '2022-03-31 16:56:39', NULL),
(403, '76767676', 'AYASTA AGAPITO MICAELA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:03:19', '2022-03-31 17:03:19', NULL),
(404, '78787878', 'BACILIO GUTIERREZ ABELINO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:07:07', '2022-03-31 17:07:07', NULL),
(405, '79797979', 'BAUTISTA VILLEGAS FORTUNATO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:21:36', '2022-03-31 17:21:36', NULL),
(406, '80808080', 'BUSTAMANTE MARTINES JOSE', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:23:46', '2022-03-31 17:23:46', NULL),
(407, '81818181', 'CARHUATANTA CASTAÑEDA ADRIANO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:30:58', '2022-03-31 17:30:58', NULL),
(408, '83838383', 'CARHUATANTA CHAVARRI ANIBAL', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:34:14', '2022-03-31 17:34:14', NULL),
(409, '84848484', 'CAICEDO BARRIOS LUIS ALBERTO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:41:48', '2022-03-31 17:41:48', NULL),
(410, '85858585', 'COTRINA ORTIZ OSCAR', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:45:58', '2022-04-25 19:58:27', '2022-04-25 19:58:27'),
(411, '86868686', 'DELGADO GUEVARA ELVA ROSA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:50:00', '2022-03-31 17:50:00', NULL),
(412, '87878787', 'ENEQUE GONZALES CECILIA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-03-31 17:56:45', '2022-03-31 17:56:45', NULL),
(413, '89898989', 'FLORES CHAPILLIQUEN JUANA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 09:45:06', '2022-04-01 09:45:06', NULL),
(414, '17826755', 'GOMEZ CUEVA JUANA VILMA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 09:50:15', '2022-04-01 09:53:29', NULL),
(415, '90909090', 'HERNANDEZ RAMIREZ SEGUNDO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 09:58:03', '2022-04-01 09:58:03', NULL),
(416, '91919191', 'CARRETERO LEVEAU RAUL YVAN', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:02:53', '2022-04-01 10:02:53', NULL),
(417, '92929292', 'LLONTOP CHAVESTA SEBASTIANA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:07:48', '2022-04-18 01:16:33', NULL),
(418, '16797638', 'MIÑOPE CHERO JUANA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '939779339', 1, NULL, '2022-04-01 10:12:04', '2022-04-18 01:17:11', NULL),
(419, '93939393', 'NECIOSUP CAICEDO MAXIMO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:17:00', '2022-04-18 01:17:28', NULL),
(420, '94949494', 'MORALES ALCANTARA ANGELICA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:20:03', '2022-04-01 10:20:03', NULL),
(421, '95959595', 'REQUE RAMIREZ ANA ELIZABETH', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:23:43', '2022-04-18 01:17:58', NULL),
(422, '96969696', 'REVILLA SANCHEZ MARIA ELENA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:30:50', '2022-04-18 01:18:17', NULL),
(423, '97979797', 'RUIZ FLORES IRENE', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:33:53', '2022-04-18 01:18:34', NULL),
(424, '98989898', 'RUIZ RODRIGUEZ MARIA DEL PILAR', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:36:51', '2022-04-01 10:36:51', NULL),
(425, '10010010', 'RUMICHE AYASTA FRANCISCO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:41:20', '2022-04-18 01:19:08', NULL),
(426, '10021002', 'SALAZAR LLUEN ISABEL', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:44:22', '2022-04-18 01:19:28', NULL),
(427, '10031003', 'SALAZAR RODRIGUEZ MIGUEL', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:48:08', '2022-04-18 01:19:49', NULL),
(428, '10041004', 'SOSA ROJAS MARIA ISABEL', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:52:08', '2022-04-18 01:20:05', NULL),
(429, '10051005', 'TORRES VARGAS LIZAURO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 10:56:28', '2022-04-18 01:20:37', NULL),
(430, '10061006', 'VELASQUEZ LLUEN JUAN CARLOS', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:00:44', '2022-04-01 11:00:44', NULL),
(431, '10071007', 'ZAVALETA DIAZ LEONILA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:03:31', '2022-04-01 11:03:31', NULL),
(432, '42461773', 'ARRIBASPLATA BARRANTES DIANA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:11:46', '2022-04-01 11:11:46', NULL),
(433, '10081008', 'AZABACHE BERNAL VICTORIA', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:16:01', '2022-04-01 11:16:01', NULL),
(434, '10091009', 'CAICEDO ARCE MANUEL', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:19:36', '2022-04-01 11:19:36', NULL),
(435, '20000000', 'CAPUÑAY GONZALES GREGORIO', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:23:52', '2022-04-01 11:23:52', NULL),
(436, '20012001', 'CORONEL SAGASTEGUI JOSE', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:27:53', '2022-04-01 11:27:53', NULL);
INSERT INTO `CLIENTE` (`CLI_CODIGO`, `CLI_DOCUMENTO`, `CLI_NOMBRES`, `CLI_DIRECCION`, `CLI_FECHA_NAC`, `CLI_DISTRITO`, `CLI_PROVINCIA`, `CLI_DEPARTAMENTO`, `CLI_EMAIL`, `CLI_TELEFONO`, `CLI_TIPO`, `CLI_REPRES_LEGAL`, `CLI_CREATED`, `CLI_UPDATED`, `CLI_DELETED`) VALUES
(437, '20022002', 'CORONEL SAGASTEGUI MARIANO', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:32:38', '2022-04-01 11:32:38', NULL),
(438, '20032003', 'CUSTODIO ESPINOZA MARIA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:37:02', '2022-04-01 11:37:02', NULL),
(439, '20042004', 'CUSTODIO LLONTO MANUEL', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:43:04', '2022-04-01 11:43:04', NULL),
(440, '20052005', 'CUSTODIO LLONTOP LEONIDAS', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '992956986', 1, NULL, '2022-04-01 11:50:09', '2022-04-01 11:50:09', NULL),
(441, '20062006', 'CUSTODIO LLONTOP MARTINA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:54:26', '2022-04-01 11:54:26', NULL),
(442, '20072007', 'CUBAS HUAMAN GENOVEVA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 11:58:37', '2022-04-01 11:58:37', NULL),
(443, '20082008', 'CRUZADO MALCA HUMBERTO', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:02:35', '2022-04-01 12:02:35', NULL),
(444, '20092009', 'ENEQUE LLUEN BENITO', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:08:14', '2022-04-01 12:08:14', NULL),
(445, '30000000', 'ENEQUE LLUEN JOSE MANUEL', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:14:34', '2022-04-18 01:25:41', NULL),
(446, '30013001', 'FARRO AGAPITO ENRIQUE', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:19:04', '2022-04-18 01:25:58', NULL),
(447, '30023002', 'FARRO CUSTODIO JOSE MERCEDES', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:24:23', '2022-04-18 01:26:14', NULL),
(448, '30033003', 'FARRO CUSTODIO TERESA MARTINA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:27:44', '2022-04-18 01:26:31', NULL),
(449, '30043004', 'FARRO CUSTODIO TERESA MARTINA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:30:12', '2022-04-21 21:28:27', '2022-04-21 21:28:27'),
(450, '30053005', 'FLORES FLORES MANUELA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:33:29', '2022-04-18 01:27:11', NULL),
(451, '30063006', 'HIDROGO HERNANDEZ ELSA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:36:27', '2022-04-18 01:27:41', NULL),
(452, '30073007', 'FUENTES ESPINOZA ANGELITA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:39:23', '2022-04-25 21:45:25', '2022-04-25 21:45:25'),
(453, '30083008', 'HORNA FARROÑAY TOMAS', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:48:47', '2022-04-18 01:28:13', NULL),
(454, '30093009', 'HORNA LLANOS CLAUDIA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:51:35', '2022-04-18 01:28:28', NULL),
(455, '40000000', 'LLUEN LLUEN JORGE', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:54:31', '2022-04-18 01:28:48', NULL),
(456, '40014001', 'LLUEN LLUEN JORGE LOT', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 12:57:43', '2022-04-01 12:57:43', NULL),
(457, '40024002', 'MIO MORALES ERADIO', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 13:01:20', '2022-04-18 01:29:19', NULL),
(458, '40034003', 'RECOBA ORTEGA VICTOR', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 13:07:43', '2022-04-18 01:29:59', NULL),
(459, '40044004', 'REQUE LIZA MARITZA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 13:14:37', '2022-04-01 13:14:37', NULL),
(460, '40054005', 'SANCHEZ CHAVEZ IRMA VIOLETA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 13:19:07', '2022-04-01 13:19:07', NULL),
(461, '40064006', ' SEGURA DE ACUÑA ANDREA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 13:21:32', '2022-04-01 13:21:32', NULL),
(462, '40074007', 'TANTARICO JIBAJA MARIA LIDIA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 13:24:18', '2022-04-18 01:30:53', NULL),
(463, '41932124', 'VELASQUEZ RAMOS JORGE LUIS', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 13:30:37', '2022-04-01 14:01:10', NULL),
(464, '40084008', 'VILLEGAS GUERRA RICARDO', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:02:21', '2022-04-25 14:29:11', '2022-04-25 14:29:11'),
(465, '40094009', 'ZELADA GONZALES JESSICA', 'Antigua Panamericana norte s/n', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:04:47', '2022-04-01 14:04:47', NULL),
(466, '50000000', 'ARTEAGA JACOBO JUAN ALEX', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:10:11', '2022-04-01 14:10:11', NULL),
(467, '50015001', 'BUSTAMANTE CIEZA ALADINO', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:13:21', '2022-04-01 14:13:21', NULL),
(468, '50025002', 'BUSTAMANTE CIEZA OLGA YOVANI', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:17:26', '2022-04-01 14:17:26', NULL),
(469, '50035003', 'CHUMACERO GARCIA JESUS', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:20:32', '2022-04-01 14:20:32', NULL),
(470, '50045004', 'CIEZA DIAZ HITALA', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:24:30', '2022-04-01 14:24:30', NULL),
(471, '50055005', 'CUSTODIO ESPINOZA JOSE', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:29:58', '2022-04-01 14:29:58', NULL),
(472, '50065006', 'CUSTODIO ESPINOZA MANUEL', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:32:41', '2022-04-01 14:32:41', NULL),
(473, '50075007', 'CUSTODIO VELASQUEZ ROSA', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:35:44', '2022-04-01 14:35:44', NULL),
(474, '50085008', 'DIAZ DELGADO EDWIN', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:38:52', '2022-04-01 14:38:52', NULL),
(475, '50095009', 'DIAZ DELGADO JULIO', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:42:09', '2022-04-01 14:42:09', NULL),
(476, '60000000', 'DE LA CRUZ CASTILLO FERNANDO AUGUSTO', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:44:29', '2022-04-01 14:44:29', NULL),
(477, '60016001', 'ENEQUE LLUEN MARITZA', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:47:11', '2022-04-01 14:47:11', NULL),
(478, '60026002', 'GONZALES CARBAJAL DORTY MARISOL', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:49:47', '2022-04-01 14:49:47', NULL),
(479, '60036003', 'GONZALES TELLO DOMITILA', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:52:16', '2022-04-01 14:53:41', NULL),
(480, '60046004', 'NUÑEZ CANCINO MOISES', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:56:48', '2022-04-01 14:56:48', NULL),
(481, '60056005', 'OLIVOS VILCHEZ MARCELINO', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 14:59:00', '2022-04-01 14:59:00', NULL),
(482, '60066006', 'TORRES QUESÑAY JOSE', 'CAJAMARCA  S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 15:01:28', '2022-04-01 15:01:28', NULL),
(483, '60076007', 'ALBERCA MEZA MARIA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 15:42:35', '2022-04-01 15:42:35', NULL),
(484, '60086008', 'AZABACHE RODRIGUEZ OSCAR', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 15:48:29', '2022-04-01 15:48:29', NULL),
(485, '60096009', 'AZABACHE VELASQUEZ MANUELA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 15:52:23', '2022-04-01 15:52:23', NULL),
(486, '70000000', 'CARHUATANTA TORRES NORA CECILIA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 15:56:18', '2022-04-01 15:56:18', NULL),
(487, '70017001', 'CHICOMA CARRASCO GERTRUDIS', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 15:59:55', '2022-04-01 15:59:55', NULL),
(488, '70027002', 'FARRO LLONTOP JOSE MANUEL', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 16:03:24', '2022-04-01 16:03:24', NULL),
(489, '70047004', 'FARRO LLONTOP RICARDO', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 16:36:26', '2022-04-01 16:36:26', NULL),
(490, '70057005', 'FERNANDEZ IPANAQUE ROLANDO', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 16:40:13', '2022-04-01 16:40:13', NULL),
(491, '70067006', 'FIESTAS CORREA LORENZA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 16:44:42', '2022-04-01 16:44:42', NULL),
(492, '70077007', 'GONZALES PISFIL MARGARITA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 16:50:24', '2022-04-01 16:50:24', NULL),
(493, '70087008', 'GRANDA CRUZ JUAN', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 16:53:32', '2022-04-01 16:53:32', NULL),
(494, '70097009', 'HERRERA HEREDIA HILDA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 16:56:41', '2022-04-01 16:56:41', NULL),
(495, '80000000', 'LAINES MECHAN JOSE ROLANDO', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 16:59:32', '2022-04-01 16:59:32', NULL),
(496, '80018001', 'LARA DIAZ HEBERT', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:04:06', '2022-04-01 17:04:06', NULL),
(497, '80028002', 'LARA DIAZ JAVIER', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:14:21', '2022-04-01 17:14:21', NULL),
(498, '80038003', 'LAZARO GALLARDO ISMAEL', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:17:36', '2022-04-01 17:17:36', NULL),
(499, '80048004', 'LLONTOP AYASTA REGINA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:20:05', '2022-04-01 17:20:05', NULL),
(500, '80058005', 'LLONTOP CHUMIOQUE MAXIMINA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:22:57', '2022-04-01 17:22:57', NULL),
(501, '80068006', 'MALCA IGNACIO IMER', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:25:51', '2022-04-01 17:25:51', NULL),
(502, '80078007', 'MECHAN ANTENE JAVIER', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:29:41', '2022-04-01 17:29:41', NULL),
(503, '80088008', 'MECHAN AZABACHE AUGUSTO', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:33:32', '2022-04-01 17:33:32', NULL),
(504, '80098009', 'MECHAN AZABACHE RAMOS', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:40:21', '2022-04-01 17:40:21', NULL),
(505, '90000000', 'MIÑOPE AZABACHE SEGUNDO PABLO', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:44:52', '2022-04-01 17:44:52', NULL),
(506, '90019001', 'MIÑOPE CHIMOY JUAN', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:52:15', '2022-04-01 17:52:15', NULL),
(507, '90029002', 'ÑIQUEN CHUMIOQUE DOLORES', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:54:22', '2022-04-01 17:54:22', NULL),
(508, '90039003', 'OLIDEN CHICOMA JUAN', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:56:28', '2022-04-01 17:56:28', NULL),
(509, '90049004', 'OLIDEN CHICOMA LORENZO', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 17:57:50', '2022-04-01 17:57:50', NULL),
(510, '90059005', 'OLIDEN CHICOMA VICTOR', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 18:04:46', '2022-04-01 18:04:46', NULL),
(511, '90069006', 'OLIDEN GONZALES YENI JACKELINE', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 18:06:12', '2022-04-01 18:06:12', NULL),
(512, '90079007', 'QUIROZ FERNANDEZ MARINA', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 18:08:42', '2022-04-01 18:08:42', NULL),
(513, '90089008', 'ROJAS OYOLA ABEL', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 18:10:31', '2022-04-01 18:10:31', NULL),
(514, '90099009', 'ROJAS TORRES JAIME', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 18:12:18', '2022-04-01 18:12:18', NULL),
(515, '12222222', 'TANTALEAN HURTADO ANTONIO', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 18:15:08', '2022-04-01 18:15:08', NULL),
(516, '13333333', 'TANTALEAN HURTADO VICTOR', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 18:16:54', '2022-04-01 18:16:54', NULL),
(517, '14444444', 'TORRES ISIQUE FIDELA CRUZ', 'FRANCISCO BOLOGNESI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-01 18:18:39', '2022-04-01 18:18:39', NULL),
(518, '15555555', 'CARHUAPOMA MEZA ESFILIA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 15:56:10', '2022-04-03 15:56:10', NULL),
(519, '16666666', 'CHAVESTA CACHAY BARTOLO', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:00:50', '2022-04-03 16:00:50', NULL),
(520, '17777777', 'CHISCUL LOCONI FELIPA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:06:16', '2022-04-03 16:06:16', NULL),
(521, '18888888', 'GUEVARA  VERA CARMEN ROSA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:10:28', '2022-04-22 18:29:51', NULL),
(522, '19999999', 'FERNANDEZ TORRES IRMA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:11:54', '2022-04-03 16:11:54', NULL),
(523, '21111111', 'FIGUEROA SALAZAR IDELSA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:19:13', '2022-04-03 16:19:13', NULL),
(524, '23333333', 'FLORES CHAVESTA JOSE MANUEL', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:23:55', '2022-04-03 16:23:55', NULL),
(525, '24444444', 'FLORES EFFIO JOSE FRANCISCO', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:31:19', '2022-04-03 16:31:19', NULL),
(526, '25555555', 'GAMARRA AZABACHE EDWIN', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:34:40', '2022-04-03 16:34:40', NULL),
(527, '26666666', 'GAMARRA AZABACHE JUANA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 16:37:54', '2022-04-03 16:37:54', NULL),
(528, '27777777', 'GONZALES ÑIQUEN ELIAS', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:16:09', '2022-04-03 17:16:09', NULL),
(529, '28888888', 'HUAMAN GONZALES ANTONIA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:21:13', '2022-04-03 17:21:13', NULL),
(530, '29999999', 'HUAYAMA HUAMAN JOSE', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:27:01', '2022-04-03 17:27:01', NULL),
(531, '31111111', 'MECHAN ROJAS LILIANA MARISOL', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:30:34', '2022-04-03 17:30:34', NULL),
(532, '32222222', 'MONSEFU CORTES VICTOR', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:33:26', '2022-04-03 17:33:26', NULL),
(533, '34444444', 'NINAQUISPE PALOMINO JAIME', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:39:15', '2022-04-03 17:39:15', NULL),
(534, '35555555', 'NINAQUISPE PALOMINO OVILMER', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:51:02', '2022-04-03 17:51:02', NULL),
(535, '36666666', 'NUÑEZ CANCINO ISAIAS', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:55:04', '2022-04-03 17:55:04', NULL),
(536, '37777777', 'ÑAÑEZ CUSTODIO PETRONILA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 17:58:06', '2022-04-03 17:58:06', NULL),
(537, '38888888', 'BERRIOS PEREZ CINTHYA NEYDI', 'AV. CESAR VALLEJO', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 18:03:55', '2022-04-25 12:33:21', NULL),
(538, '39999999', 'PARRA TIQUILLAHUANCA MARIA  ARLITA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 18:11:43', '2022-04-03 18:11:43', NULL),
(539, '41111111', 'ROJAS QUISPE DE DIAZ LUZ DEL CARMEN', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 18:16:04', '2022-04-03 18:20:40', NULL),
(540, '42222222', 'ROJAS REQUEJO ROBERTO', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 18:46:48', '2022-04-03 18:46:48', NULL),
(541, '43333333', 'SANCHEZ DIAZ FRANCO', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 19:00:29', '2022-04-03 19:00:29', NULL),
(542, '45555555', 'SAUCEDO CALDERON PASTORA', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 19:09:19', '2022-04-03 19:09:19', NULL),
(543, '46666666', 'SOSA ROJAS WILMER', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 19:12:47', '2022-04-03 19:12:47', NULL),
(544, '47777777', 'VILLEGAS ENEQUE JORGE LUIS', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 19:16:03', '2022-04-03 19:16:03', NULL),
(545, '48888888', 'VILLEGAS ENEQUE JOSE CLEMENTE', 'JOSE OLAYA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 19:19:17', '2022-04-25 14:43:28', '2022-04-25 14:43:28'),
(546, '49999999', 'ADAM GEB RUIZ PAREDES MARIA', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 19:25:23', '2022-04-18 02:08:03', NULL),
(547, '51111111', 'CUSTODIO GUZMAN PEDRO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 19:30:14', '2022-04-03 19:30:14', NULL),
(548, '52222222', 'CUSTODIO GUZMAN SEGUNDO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 19:59:14', '2022-04-03 19:59:14', NULL),
(549, '53333333', 'FLORES EFFIO JOSE FRANCISCO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:02:10', '2022-04-03 20:02:10', NULL),
(550, '54444444', 'FLORES EFFIO MANUEL', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:14:50', '2022-04-03 20:14:50', NULL),
(551, '56666666', 'GONZALES GONZALES JOSE', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:19:55', '2022-04-03 20:19:55', NULL),
(552, '57777777', 'GONZALES VELASQUEZ LUIS', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:22:42', '2022-04-03 20:22:42', NULL),
(553, '58888888', 'GUZMAN SANCHEZ RAMOS', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:26:16', '2022-04-03 20:26:16', NULL),
(554, '59999999', 'JIBAJA CRUZ ZORAIDA', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:31:36', '2022-04-03 20:31:36', NULL),
(555, '61111111', 'LUNA HUAMAN FRUCTUOSO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:34:13', '2022-04-03 20:34:13', NULL),
(556, '62222222', 'MIÑOPE CACHAY JOSE ANTONIO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:37:25', '2022-04-03 20:37:25', NULL),
(557, '63333333', 'MIRES FERNANDEZ VICTORIA ELENA', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:41:59', '2022-04-03 20:41:59', NULL),
(558, '64444444', 'MIREZ MEJIA MARCIAL', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:44:46', '2022-04-03 20:44:46', NULL),
(559, '65555555', 'PEREZ CUSTODIO JESUS ITALO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:54:38', '2022-04-18 02:12:28', NULL),
(560, '67777777', 'PACHERRES VELASQUEZ VDA. DE DIAZ', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 20:58:44', '2022-04-03 20:58:44', NULL),
(561, '68888888', 'RUBIO RIVERA LORENZO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:02:31', '2022-04-18 02:13:09', NULL),
(562, '69999999', 'SALAZAR RODRIGUEZ FAUSTO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:05:26', '2022-04-18 02:13:28', NULL),
(563, '71111111', 'SIESQUEN JUAREZ NANCY', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:08:07', '2022-04-18 02:13:45', NULL),
(564, '72222222', 'VELA INGA PETRONILA', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:10:29', '2022-04-18 02:14:02', NULL),
(565, '73333333', 'VELASQUEZ RODRIGUEZ MANUEL', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:14:17', '2022-04-03 21:14:17', NULL),
(566, '74444444', 'VELASQUEZ RODRIGUEZ MARINO', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:17:08', '2022-04-18 02:14:42', NULL),
(567, '75555555', 'VELASQUEZ RODRIGUEZ MIGUEL', 'Manuel Pardo S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:19:23', '2022-04-18 02:14:58', NULL),
(568, '76666666', 'AGAPITO LLONTOP ANDRES', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:43:48', '2022-04-03 21:43:48', NULL),
(569, '78888888', 'AZABACHE CHANAME ISABEL', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 21:47:28', '2022-04-03 21:47:28', NULL),
(570, '79999999', 'BARDALES MENDOZA JESUS', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 22:20:09', '2022-04-03 22:20:56', NULL),
(571, '81111111', 'BARDALES DIAZ ROSA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 22:24:47', '2022-04-03 22:24:47', NULL),
(572, '82222222', 'BENAVIDEZ RODRIGUEZ ENRIQUE', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 22:28:05', '2022-04-03 22:28:05', NULL),
(573, '84444444', 'CARBONEL RAMIREZ CLARA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 22:33:39', '2022-04-03 22:33:39', NULL),
(574, '85555555', 'CHUMIOQUE CHAFLOQUE DIONICIA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 22:37:17', '2022-04-03 22:37:17', NULL),
(575, '86666666', 'CONGREGACION EVANGELICA SEPTMO DIA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 22:43:06', '2022-04-03 22:43:06', NULL),
(576, '87777777', 'CUBAS HUAMAN CRISTOBAL', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 22:48:02', '2022-04-03 22:48:02', NULL),
(577, '89999999', 'DELGADO MORENO ANA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 23:21:00', '2022-04-03 23:33:41', NULL),
(578, '91111111', 'EFFIO UYPAN ABELINA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 23:55:26', '2022-04-03 23:55:26', NULL),
(579, '92222222', 'GASTELO LLANOS JOSE JACINTO', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-03 23:59:08', '2022-04-03 23:59:08', NULL),
(580, '94444444', 'GONZALES BALLENA MARIA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:03:03', '2022-04-04 00:03:03', NULL),
(581, '95555555', 'GUEVARA RAMIREZ MARIA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:05:47', '2022-04-04 00:05:47', NULL),
(582, '96666666', 'HERRERA GAMARRA JORGE', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:08:48', '2022-04-18 02:19:41', NULL),
(583, '97777777', 'HERRERA GAMARRA PATRICIA YOVANA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:11:45', '2022-04-04 00:11:45', NULL),
(584, '98888888', 'HORNA PIEDRA TEODOMIRO', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:14:41', '2022-04-04 00:14:41', NULL),
(585, '12333333', 'LOPEZ GREGORIO MARCELINA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:20:35', '2022-04-18 02:20:54', NULL),
(586, '23444444', 'MIO CARRASCO JULIO', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:23:55', '2022-04-18 02:21:17', NULL),
(587, '34555555', 'MONTALVAN CISNEROS HERVIS', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:26:49', '2022-04-18 02:21:37', NULL),
(588, '45666666', 'OBANDO CAPUÑAY BLANCA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:30:48', '2022-04-26 02:35:27', '2022-04-26 02:35:27'),
(589, '56777777', 'OBANDO CAPUÑAY MARITZA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:34:13', '2022-04-04 00:34:13', NULL),
(590, '67888888', 'PACHERREZ INCIO DAMIAN', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:36:44', '2022-04-18 02:22:25', NULL),
(591, '12345678', 'PIEDRA CARRERO EMELINA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:41:08', '2022-04-18 02:22:41', NULL),
(592, '23456789', 'PIEDRA CARRERO SIXTO', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:44:00', '2022-04-18 02:23:02', NULL),
(593, '34567891', 'PUYEN FERRE BERNARDINA', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 00:46:21', '2022-04-18 02:23:19', NULL),
(594, '01222222', 'ALBERCA ALVAREZ MERLY', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 15:46:00', '2022-04-04 15:46:00', NULL),
(595, '02222222', 'BUSTAMANTE CIEZA ARMANDINA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:01:27', '2022-04-04 16:01:27', NULL),
(596, '87654321', 'BUSTAMANTE CIEZA GILBERTO', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:10:48', '2022-04-04 16:10:48', NULL),
(597, '76543210', 'BUSTAMANTE CORONEL CLARA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:17:23', '2022-04-04 16:17:23', NULL),
(598, '65432109', 'BUSTAMANTE CORONEL JOSE ELI', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:23:36', '2022-04-04 16:23:36', NULL),
(599, '54321098', 'BUSTAMANTE CORONEL REYNA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:28:24', '2022-04-04 16:28:24', NULL),
(600, '43210987', 'BURGA CIEZA JESUS', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:33:36', '2022-04-25 14:28:58', '2022-04-25 14:28:58'),
(601, '32109876', 'CAMPOS BRAVO IVETTE', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:46:56', '2022-04-04 16:46:56', NULL),
(602, '21098765', 'CAMPOS GUEVARA ELMER', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:52:52', '2022-04-04 16:52:52', NULL),
(603, '10987654', 'CAMPOS TORRES LUCY MARLENY', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 16:58:10', '2022-04-25 15:54:35', '2022-04-25 15:54:35'),
(604, '01234567', 'CIEZA DIAZ DENIS', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:01:21', '2022-04-04 17:01:21', NULL),
(605, '01010101', 'CORDOVA SANDOVAL ALESSANDRA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:08:57', '2022-04-04 17:08:57', NULL),
(606, '02020202', 'DIAZ RUIZ RUTH MILENY', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:20:37', '2022-04-04 17:20:37', NULL),
(607, '03030303', 'CIEZA CORONEL TERESA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:24:13', '2022-04-04 17:24:13', NULL),
(608, '04040404', 'CORRALES FERNANDEZ ANANIAS', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:26:50', '2022-04-04 17:26:50', NULL),
(609, '05050505', 'DIAZ CASTILLO WILL ALBERTO', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:31:05', '2022-04-04 17:31:05', NULL),
(610, '06060606', 'FERNANDEZ FLORES ERNESTO', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:34:01', '2022-04-04 17:34:01', NULL),
(611, '07070707', 'GUERRERO CLAVO MERLY', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:37:30', '2022-04-04 17:37:30', NULL),
(612, '08080808', 'BURGA SILVA JORGE LENIN', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:40:50', '2022-04-04 17:40:50', NULL),
(613, '09090909', 'GUEVARA VERA LUZMILA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:44:30', '2022-04-04 17:44:30', NULL),
(614, '11223344', 'IDROGO GONZALES JUAN CARLOS', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:54:18', '2022-04-04 17:54:18', NULL),
(615, '22334455', 'MALHABER MANAYAY MARIA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 17:58:39', '2022-04-04 17:58:39', NULL),
(616, '33445566', 'MECHAN CABRERA MAGALY JANETH', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:01:54', '2022-04-04 18:01:54', NULL),
(617, '44556677', 'PISFIL CUSTODIO CATALINO', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:04:44', '2022-04-04 18:04:44', NULL),
(618, '55667788', 'RODRIGUEZ MECHAN CARLOS', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:07:19', '2022-04-04 18:07:19', NULL),
(619, '66778899', 'RODRIGUEZ MECHAN MAGDALENA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:11:23', '2022-04-04 18:11:23', NULL),
(620, '77889900', 'RODRIGUEZ MECHAN PEDRO', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:14:57', '2022-04-04 18:14:57', NULL),
(621, '88990011', 'ROJAS PRINCIPE SANTOS YOEL', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:18:13', '2022-04-04 18:18:13', NULL),
(622, '99001122', 'SANTOS GARCIA PATRICIA AURORA', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:20:45', '2022-04-04 18:20:45', NULL),
(623, '00112233', 'VALDIVIEZO ZAVALA GLADIS ELIZABETH', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:23:43', '2022-04-04 18:23:43', NULL),
(624, '09876543', 'VENTURA DIAZ DARLYN', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:26:33', '2022-04-04 18:26:33', NULL),
(625, '88776655', 'ZOLORZANO ADRIANZEN WILFREDO', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:28:52', '2022-04-04 18:28:52', NULL),
(626, '23123123', 'BARDALES DIAZ CRISTOBAL', 'LETICIA S/N', '0200-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:45:17', '2022-04-04 18:45:17', NULL),
(627, '23132313', 'BURGA DIAZ CARLOS ALBERTO', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:49:38', '2022-04-04 18:49:38', NULL),
(628, '32423242', 'CARDENAS MIRES IZAMAR', 'MARIANO MELGAR S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:52:39', '2022-04-04 18:52:39', NULL),
(629, '34433443', 'CHAVESTA CAICEDO FLOR', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 18:57:13', '2022-04-04 18:57:13', NULL),
(630, '21122112', 'SALAZAR PUYEN WALTER', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:00:30', '2022-04-04 19:00:30', NULL),
(631, '23322332', 'ESPINOZA ZARZOSA JOSE BENITO', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:12:26', '2022-04-04 19:12:26', NULL),
(632, '45544554', 'FLORES EFFIO ALEJANDRO', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:16:02', '2022-04-04 19:16:02', NULL),
(633, '56655665', 'FLORES EFFIO GENARO', 'SAN LUIS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:19:07', '2022-04-04 19:19:07', NULL),
(634, '65566565', 'FLORES GONZALES  JOEL ALEJANDRO', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:21:48', '2022-04-04 19:21:48', NULL),
(635, '78877887', 'FLORES GONZALES DINA YRIS', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:26:14', '2022-04-04 19:26:14', NULL),
(636, '98899889', 'GOMEZ CHUQUINES AUGUSTO', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:30:08', '2022-04-04 19:30:08', NULL),
(637, '00000001', 'GUEVARA RAMIREZ JUAN', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:35:57', '2022-04-04 19:35:57', NULL),
(638, '00000002', 'RODRIGUEZ SECLEN CARLOS', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:38:43', '2022-04-04 19:38:43', NULL),
(639, '00000003', 'SALAZAR GONZALES NICOLASA', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:42:04', '2022-04-04 19:42:04', NULL),
(640, '00000004', 'VASQUEZ CALDERON DE GARCIA  ANA', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:45:24', '2022-04-04 19:45:24', NULL),
(641, '00000005', 'ANACLETO NOMBERA WILMER', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:53:22', '2022-04-04 19:56:40', NULL),
(642, '00000006', 'CHAVEZ RODRIGUEZ ANA ISABEL', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 19:59:24', '2022-04-04 19:59:24', NULL),
(643, '00000007', 'CHUNGA SALAZAR FELICITA', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:03:14', '2022-04-04 20:03:14', NULL),
(644, '00000009', 'CRUZ VALLEJOS ELOY', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:06:14', '2022-04-04 20:06:14', NULL),
(645, '00000011', 'FERNANDEZ PEREZ ROSA', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:09:58', '2022-04-04 20:09:58', NULL),
(646, '00000022', 'FLORES FLORES  JUAN', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:15:22', '2022-04-04 20:18:57', NULL),
(647, '00000033', 'GAMARRA GUZMAN CESAR', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:20:58', '2022-04-04 20:20:58', NULL),
(648, '00000044', 'GAMARRA GUZMAN LUIS', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:25:06', '2022-04-04 20:25:06', NULL),
(649, '00000077', 'HERRERA  HORNA JAVIER', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:29:37', '2022-04-04 20:29:37', NULL),
(650, '00000088', 'LUNA HUAMAN FRUCTUOSO', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:32:47', '2022-04-04 20:32:47', NULL),
(651, '00000099', 'MUNDACA MONTALVO ENRIQUE', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:36:40', '2022-04-04 20:36:40', NULL),
(652, '00000111', 'PISFIL GAVIDIA FEDERICO', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:40:24', '2022-04-04 20:40:24', NULL),
(653, '00000222', 'SERRATO MAZA MARUJA', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:43:26', '2022-04-04 20:43:26', NULL),
(654, '00000333', 'ZELADA SUAREZ INES', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 20:46:32', '2022-04-04 20:46:32', NULL),
(655, '00000444', 'ALVARADO HIDALGO ARMIDA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 21:09:20', '2022-04-04 21:09:20', NULL),
(656, '00000555', 'ASABACHE CHANAME SEGUNDO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 21:19:01', '2022-04-04 21:19:01', NULL),
(657, '00000666', 'BRAVO MENDOZA EDITH', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-04 21:23:36', '2022-04-04 21:23:36', NULL),
(658, '00000777', 'BURGOS VILLEGAS WILMER', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 00:04:02', '2022-04-05 00:04:02', NULL),
(659, '00000888', 'CALDERON RODRIGO QUEYLI', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 00:12:49', '2022-04-05 00:12:49', NULL),
(660, '00000999', 'CASTILLO ALCANTARA JUSTO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 00:21:05', '2022-04-05 00:21:05', NULL),
(661, '00001111', 'CENTURION ALARCON FLOR MARIA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 00:28:14', '2022-04-05 00:28:14', NULL),
(662, '00002222', 'CHICOMA SOPLAPUCO SEBASTIANA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 00:41:44', '2022-04-05 00:41:44', NULL),
(663, '00003333', 'CIEZA ROJAS ROSALINA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 00:45:52', '2022-04-05 00:45:52', NULL),
(664, '00004444', 'CIEZA ROJAS VICENTE', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 00:54:05', '2022-04-05 00:54:05', NULL),
(665, '00005555', 'HUANCAS CIEZA IRENE', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 01:04:10', '2022-04-05 01:04:10', NULL),
(666, '00006666', 'CUBAS BERMEO ISABEL', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 15:19:13', '2022-04-05 15:19:13', NULL);
INSERT INTO `CLIENTE` (`CLI_CODIGO`, `CLI_DOCUMENTO`, `CLI_NOMBRES`, `CLI_DIRECCION`, `CLI_FECHA_NAC`, `CLI_DISTRITO`, `CLI_PROVINCIA`, `CLI_DEPARTAMENTO`, `CLI_EMAIL`, `CLI_TELEFONO`, `CLI_TIPO`, `CLI_REPRES_LEGAL`, `CLI_CREATED`, `CLI_UPDATED`, `CLI_DELETED`) VALUES
(667, '00007777', 'ENEQUE CUSTODIO CRUZ', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 15:22:49', '2022-04-05 15:27:31', NULL),
(668, '00008888', 'DIAZ HERRERA BETTY', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 15:31:01', '2022-04-05 15:31:01', NULL),
(669, '00009999', 'FARRO AGAPITO JUANA LILIANA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 15:38:58', '2022-04-05 15:38:58', NULL),
(670, '00011111', 'FARRO LLONTOP MARILU', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 15:46:52', '2022-04-05 15:46:52', NULL),
(671, '00022222', 'GAMARRA TULLUME PEDRO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 15:50:14', '2022-04-25 16:31:12', '2022-04-25 16:31:12'),
(672, '00033333', 'GASTELO SENMACHE MARIA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 15:55:50', '2022-04-05 15:55:50', NULL),
(673, '00044444', 'GONZALES AZABACHE JULIA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 16:00:50', '2022-04-05 16:00:50', NULL),
(674, '00055555', 'GONZALES RAMIREZ FELICIANO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 16:03:44', '2022-04-05 16:03:44', NULL),
(675, '00066666', 'GONZALES RODRIGUEZ CASIMIRO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 16:12:08', '2022-04-05 16:12:08', NULL),
(676, '00077777', 'GONZALES RODRIGUEZ JESUS', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 16:23:57', '2022-04-05 16:23:57', NULL),
(677, '00088888', 'GUZMAN QUISPE EDUARDO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 16:27:41', '2022-04-05 16:27:41', NULL),
(678, '00099999', 'HERRERA  FERNANDEZ JUAN', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:11:13', '2022-04-05 17:11:13', NULL),
(679, '00111111', 'HERRERA HORNA  ENRIQUE', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:17:39', '2022-04-05 17:17:39', NULL),
(680, '00222222', 'ROJAS HERNANDEZ VILMA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:20:09', '2022-04-05 17:20:09', NULL),
(681, '00333333', 'YOVERA CORREA PAULA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:23:20', '2022-04-05 17:23:20', NULL),
(682, '00444444', 'LLUNCOR GONZALES ALEJANDRO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:26:57', '2022-04-05 17:26:57', NULL),
(683, '00555555', 'MECHAN AZABACHE WILFREDO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:30:07', '2022-04-05 17:30:07', NULL),
(684, '00666666', 'MECHAN GONZALES WILMER', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:34:27', '2022-04-26 02:23:29', '2022-04-26 02:23:29'),
(685, '00777777', 'MEDINA QUIROZ OSCAR NERI', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:41:44', '2022-04-05 17:41:44', NULL),
(686, '00888888', 'MEJIA CAMPOS HUGO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:45:21', '2022-04-05 17:45:21', NULL),
(687, '00999999', 'MONTALVO BARBOZA MARIA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:49:33', '2022-04-05 17:49:33', NULL),
(688, '01111111', 'OLIDEN DELGADO ANGEL', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:53:10', '2022-04-05 17:53:10', NULL),
(689, '11112222', 'ORDOÑEZ GUEVARA EDGAR', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 17:56:29', '2022-04-05 17:56:29', NULL),
(690, '22223333', 'PASION ARANDA EUGENIA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:02:42', '2022-04-05 18:02:42', NULL),
(691, '33334444', 'PERALTA GUEVARA EMILIA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:13:16', '2022-04-05 18:13:16', NULL),
(692, '44445555', 'PUELLES JIMENEZ MARIA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:17:36', '2022-04-05 18:17:36', NULL),
(693, '55556666', 'QUISPE VASQUEZ ABSALON', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:19:59', '2022-04-05 18:19:59', NULL),
(694, '66667777', 'QUISPE VASQUEZ BERTHA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:24:37', '2022-04-05 18:24:37', NULL),
(695, '77778888', 'RODRIGUEZ MIÑOPE ANDREA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:30:45', '2022-04-05 18:30:45', NULL),
(696, '88889999', 'ROJAS CARHUATANTA DIANA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:35:29', '2022-04-05 18:35:29', NULL),
(697, '99990000', 'RUIZ SAAVEDRA  HUGO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '000000000', 1, NULL, '2022-04-05 18:40:40', '2022-04-18 02:59:29', NULL),
(698, '99887766', 'SANCHEZ EFFIO CESAR AUGUSTO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:49:23', '2022-04-05 18:49:23', NULL),
(699, '45632178', 'SANTOS NIMA LIZBETH MERLY', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:51:43', '2022-04-05 18:51:43', NULL),
(700, '23789055', 'SEVILLA MORENO LUZ MARY', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:54:30', '2022-04-05 18:54:30', NULL),
(701, '67894532', 'SOTO HERRERA NIXON PAZ', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 18:57:46', '2022-04-05 18:57:46', NULL),
(702, '55667780', 'TACURY LOPEZ ABSALON', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 19:00:38', '2022-04-05 19:00:38', NULL),
(703, '00987654', 'TEJADA FERNANDEZ VICTOR', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 19:09:29', '2022-04-05 19:09:29', NULL),
(704, '77869540', 'VASQUEZ CHOZO ALBERTO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 19:13:26', '2022-04-05 19:13:26', NULL),
(705, '98750043', 'VILLEGAS TORRES SANTOS', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 19:16:39', '2022-04-05 19:16:39', NULL),
(706, '11227788', 'VASQUEZ FERNANDEZ  MARILU', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 19:23:48', '2022-04-05 19:23:48', NULL),
(707, '87684936', 'BANCES FARROÑAN MARIA', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 19:37:26', '2022-04-05 19:37:26', NULL),
(708, '66655544', 'CHERO CAPUÑAY IRENE', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 20:10:48', '2022-04-05 20:10:48', NULL),
(709, '44667888', 'CHERO CAPUÑAY MANUEL', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 20:15:29', '2022-04-05 20:15:29', NULL),
(710, '55443388', 'CORONEL MEJIA DORALIZA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 20:18:51', '2022-04-05 20:18:51', NULL),
(711, '99554433', 'CORONEL MEJIA JAMES', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 20:22:01', '2022-04-05 20:22:01', NULL),
(712, '33557700', 'DELGADO CIEZA MARIA SANTOS', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 20:24:40', '2022-04-05 20:24:40', NULL),
(713, '22557700', 'DIAZ DE QUIROZ NORMA ISABEL', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 20:52:08', '2022-04-05 20:52:08', NULL),
(714, '77664433', 'DOMINGUEZ PEREZ TANIA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 20:56:44', '2022-04-05 20:56:44', NULL),
(715, '11556677', 'ESPINOZA CUSTODIO BERTHA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 20:59:51', '2022-04-05 20:59:51', NULL),
(716, '44669922', 'FARRO AYASTA JAVIER', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:04:08', '2022-04-05 21:04:08', NULL),
(717, '70676544', 'FARRO CHAVESTA FELICITA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:08:22', '2022-04-05 21:08:22', NULL),
(718, '45690766', 'FARRO CHAVESTA GUILLERMO', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:11:28', '2022-04-05 21:11:28', NULL),
(719, '29000033', 'FARRO CHAVESTA MIGUEL ANGEL', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:15:24', '2022-04-05 21:15:24', NULL),
(720, '11000001', 'FARRO SENMACHE  TERESA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:18:58', '2022-04-05 21:18:58', NULL),
(721, '22000002', 'FERNANDEZ LOZANO LUZDINA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:24:04', '2022-04-05 21:24:04', NULL),
(722, '33000003', 'FERRE GONZALES JUAN', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:31:39', '2022-04-05 21:31:39', NULL),
(723, '44000004', 'FLORES LLUEN CESAR ORLANDO', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:34:42', '2022-04-05 21:34:42', NULL),
(724, '55000005', 'FLORES RELUZ EVER', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:37:11', '2022-04-05 21:37:11', NULL),
(725, '66000006', 'GONZALES SANCHEZ CLEMENTE', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:40:55', '2022-04-05 21:40:55', NULL),
(726, '77000007', 'GUZMAN CHAVESTA WILMER', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:44:25', '2022-04-05 21:44:25', NULL),
(727, '88000008', 'HUAMAN  MALCA LUCILA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:47:16', '2022-04-05 21:47:16', NULL),
(728, '99000009', 'I.E.P. SAN PEDRO', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 21:56:50', '2022-04-05 21:56:50', NULL),
(729, '11000011', 'LLUMPO VALENCIA JUAN', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:01:10', '2022-04-05 22:01:10', NULL),
(730, '22000022', 'LOPEZ PEDRAZA OSCAR', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:04:10', '2022-04-05 22:04:10', NULL),
(731, '33000033', 'LOPEZ PEDRAZA JOSE', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:07:59', '2022-04-05 22:07:59', NULL),
(732, '44000044', 'LOZANO DIAZ DORIS', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:14:14', '2022-04-05 22:14:14', NULL),
(733, '55000055', 'MIÑOPE CHANCAFE JORGE MIGUEL', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:18:22', '2022-04-05 22:18:22', NULL),
(734, '66000066', 'MIÑOPE CHANCAFE MARIA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:22:09', '2022-04-05 22:22:09', NULL),
(735, '77000077', 'MONJA CRUZ ERLINDA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:25:18', '2022-04-05 22:25:18', NULL),
(736, '88000088', 'MONTALVAN SOSA MANUEL', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:27:57', '2022-04-05 22:27:57', NULL),
(737, '99000099', 'NAVARRO COLOMA SARA DE LOS MILAGROS', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:32:52', '2022-04-05 22:32:52', NULL),
(738, '11111110', 'PEREZ CUSTODIO JAVIER', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:53:47', '2022-04-05 22:53:47', NULL),
(739, '22222220', 'PINZON LOYAGA CLORINDA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:57:00', '2022-04-05 22:57:00', NULL),
(740, '33333330', 'PISFIL CHAFLOQUE BERTHA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 22:59:28', '2022-04-05 22:59:28', NULL),
(741, '44444440', 'PISFIL PINEDO LUIS ALBERTO', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:04:22', '2022-04-05 23:04:22', NULL),
(742, '55555550', 'PEREZ MENA ALEJANDRO', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:07:07', '2022-04-05 23:07:07', NULL),
(743, '66666660', 'ROMERO ALCANTARA AGUSTIN', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:12:22', '2022-04-05 23:12:22', NULL),
(744, '77777770', 'RUIZ BURGA ARMANDINA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:15:56', '2022-04-05 23:15:56', NULL),
(745, '88888880', 'SAMILLAN VASQUEZ FRANCISCO', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:21:00', '2022-04-05 23:21:00', NULL),
(746, '99999990', 'SANCHEZ EFFIO ANDREA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:23:44', '2022-04-05 23:23:44', NULL),
(747, '11111100', 'SANCHEZ LEYVA NOEMI', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:27:47', '2022-04-05 23:27:47', NULL),
(748, '22222200', 'SANCHEZ SANDOVAL JESUS DE LOS MILAGROS', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:30:14', '2022-04-05 23:30:14', NULL),
(749, '33333300', 'SANDOVAL FARROÑAN MARIA MANUELA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:33:08', '2022-04-05 23:33:08', NULL),
(750, '55555500', 'SANTAMARIA RAMIREZ YASMIN', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:35:36', '2022-04-05 23:35:36', NULL),
(751, '66666600', 'SAUCEDO DIAZ EIDA MARITA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:38:33', '2022-04-05 23:38:33', NULL),
(752, '77777700', 'SILVA GARCIA DAVID', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:41:10', '2022-04-05 23:41:10', NULL),
(753, '88888800', 'SILVA RAZURI DAVID', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:44:21', '2022-04-05 23:44:21', NULL),
(754, '99999900', 'SILVA RAZURI NANCY  RAQUEL', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:48:07', '2022-04-05 23:48:07', NULL),
(755, '11111000', 'SILVA RAZURI MARY CARMEN', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:51:13', '2022-04-05 23:51:13', NULL),
(756, '22222000', 'TIPARRA QUESQUEN JUAN', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:53:18', '2022-04-05 23:53:18', NULL),
(757, '33333000', 'TORRES ISIQUE JOSE DEL CARMEN', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:55:45', '2022-04-05 23:55:45', NULL),
(758, '44444000', 'UYPAN DE CRIOLLO ROSA ELVIRA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-05 23:58:14', '2022-04-05 23:58:14', NULL),
(759, '55555000', 'VILLALOBOS CRUZ ROSALIA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 00:03:19', '2022-04-06 00:03:19', NULL),
(760, '66666000', 'YEPEZ PEÑA DE ROJAS LIDIA', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 00:06:24', '2022-04-06 00:06:24', NULL),
(761, '77777000', 'YPANAQUE ESPINOZA DANNY', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 00:08:41', '2022-04-06 00:08:41', NULL),
(762, '88888000', 'CALDERON RAMOS DONATILA', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 09:46:31', '2022-04-06 10:05:22', NULL),
(763, '99999000', 'BUSTAMANTE BACA CARLOS', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:08:34', '2022-04-06 10:08:34', NULL),
(764, '11110000', 'BUSTAMANTE CIEZA GENARO', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:19:58', '2022-04-06 10:19:58', NULL),
(765, '22220000', 'AYASTA MUÑOZ CARLOS', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:23:45', '2022-04-06 10:23:45', NULL),
(766, '33330000', 'CHAFLOQUE PUICON ISMAEL', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:26:33', '2022-04-06 10:26:33', NULL),
(767, '44440000', 'CLAVO RIMARACHIN MARINO', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:31:03', '2022-04-06 10:31:03', NULL),
(768, '55550000', 'ESPINOZA AYASTA BERNARDO', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:36:02', '2022-04-06 10:36:02', NULL),
(769, '66660000', 'ESPINOZA AYASTA SANTOS', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:42:57', '2022-04-06 10:42:57', NULL),
(770, '77770000', 'ESPINOZA LAINES JOSE LUIS', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:47:26', '2022-04-06 10:47:26', NULL),
(771, '88880000', 'ESPINOZA LAINES SANTIAGO', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:51:10', '2022-04-06 10:51:10', NULL),
(772, '99900000', 'ESPINOZA LLUEN SANTIAGO', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 10:57:38', '2022-04-06 10:57:38', NULL),
(773, '11100000', 'ESPINOZA NICOLAS GABRIEL', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:07:38', '2022-04-06 11:07:38', NULL),
(774, '22200000', 'ESPINOZA NICOLAS SEGUNDO', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:13:16', '2022-04-06 11:13:16', NULL),
(775, '44400000', 'FLORIAN PAREDES ROBERTH ABRHAM', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:19:56', '2022-04-06 11:19:56', NULL),
(776, '55500000', 'GAMARRA ESPINOZA CATALINO', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:24:13', '2022-04-06 11:24:13', NULL),
(777, '66600000', 'RENTERIA CARRASCO JOSE', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:26:54', '2022-04-06 11:26:54', NULL),
(778, '77700000', 'GONZALES CAPUÑAY JOSE DEL CARMEN', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:29:35', '2022-04-06 11:29:35', NULL),
(779, '88800000', 'INVERSIONES DOS DELFINES SRL SUITE VIP', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:35:50', '2022-04-06 11:35:50', NULL),
(780, '99900001', 'LARI PEZZO MASS', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:40:30', '2022-04-06 11:40:30', NULL),
(781, '11000000', 'MIMBELA BALLENA MARTHA', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:46:12', '2022-04-06 11:46:12', NULL),
(782, '22000000', 'MORA ESPINOZA JOSE', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:52:42', '2022-04-06 11:52:42', NULL),
(783, '33000000', 'MOROCHO PEÑA DIANA DELFINA', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 11:56:46', '2022-04-06 11:56:46', NULL),
(784, '55000000', 'PISFIL ESPINOZA CESAR AUGUSTO', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 12:00:18', '2022-04-06 12:00:18', NULL),
(785, '66000000', 'PISFIL ESPINOZA MARIA DOLORES', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 12:06:35', '2022-04-06 12:06:35', NULL),
(786, '88000000', 'PISFIL ESPINOZA MARGARITA', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 12:19:31', '2022-04-06 12:26:36', NULL),
(787, '77000000', 'SANTOS TOCTO JORGE', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 12:38:04', '2022-04-06 12:38:04', NULL),
(788, '99000000', 'GAMARRA ESPINOZA ISABEL', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 12:43:18', '2022-04-06 12:43:18', NULL),
(789, '10000000', 'VELASQUEZ ESPINOZA ROSA', 'PUENTE GRANDE S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 12:45:45', '2022-04-06 12:45:45', NULL),
(790, '20000001', 'ARRIOLA FERNANDEZ JUDITH', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 13:02:17', '2022-04-06 13:02:17', NULL),
(791, '30000001', 'CAMPOS PAREDES JUAN CARLOS', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 13:16:19', '2022-04-06 13:16:19', NULL),
(792, '40000001', 'CENTURION ALARCON  MARCOS', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 13:35:09', '2022-04-06 13:35:09', NULL),
(793, '50000001', 'CENTURION CARRASCO LUIS', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 13:40:18', '2022-04-06 13:40:18', NULL),
(794, '60000001', 'CESPEDES HUAMAN MARIA', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 13:44:01', '2022-04-06 13:44:01', NULL),
(795, '70000001', 'CORDOVA ROSALES SANTOS', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:25:32', '2022-04-06 14:25:32', NULL),
(796, '80000001', 'CUSTODIO GUZMAN MARLENY', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:29:34', '2022-04-06 14:29:34', NULL),
(797, '99999991', 'FARRO LLONTOP ROSA MARIA', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:32:13', '2022-04-06 14:32:13', NULL),
(798, '22222211', 'FARRO LLONTOP RICARDO', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:35:53', '2022-04-06 14:35:53', NULL),
(799, '33333311', 'FERNANDEZ IPANAQUE FIDEL', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:39:22', '2022-04-06 14:39:22', NULL),
(800, '44444411', 'FERNANDEZ IPANAQUE ORLANDO', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:42:24', '2022-04-06 14:42:24', NULL),
(801, '55555511', 'FERNANDEZ ZELADA DONATILA', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:44:54', '2022-04-06 14:44:54', NULL),
(802, '66666611', 'GAMARRA LAINES SEBASTIAN', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:54:20', '2022-04-06 14:54:20', NULL),
(803, '77777711', 'GONZALES RODRIGUEZ JESUS', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 14:57:51', '2022-04-06 14:57:51', NULL),
(804, '88888811', 'HUAYAMA QUISPE ELMER', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:00:05', '2022-04-06 15:00:05', NULL),
(805, '11119999', 'LLUEN PISFIL JAIME MANUEL', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:03:29', '2022-04-06 15:03:29', NULL),
(806, '22229999', 'VELASQUEZ GARCIA SANTOS GUILLERMO', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:06:13', '2022-04-06 15:06:13', NULL),
(807, '33339999', 'OLIDEN SOSA LOURDES', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:12:22', '2022-04-06 15:12:22', NULL),
(808, '44449999', 'RAMIREZ ANTESANA JESSICA', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:15:50', '2022-04-18 03:37:43', NULL),
(809, '55559999', 'REQUE GONZALES ANTONIA', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:18:08', '2022-04-18 03:38:10', NULL),
(810, '66669999', 'SANCHEZ PARDO BETTY', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:21:03', '2022-04-26 02:58:20', '2022-04-26 02:58:20'),
(811, '77779999', 'SANTAMARIA BALDERA ROSA', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:25:40', '2022-04-18 03:39:04', NULL),
(812, '99991111', 'SOTO HERRERA ANDRES', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:28:33', '2022-04-18 03:39:28', NULL),
(813, '16594401', 'TANTALEAN OLIVERA MARIANO', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:32:11', '2022-04-09 15:10:13', NULL),
(814, '77772222', 'VASQUEZ VILLANUEVA CESAR', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:38:17', '2022-04-06 15:38:17', NULL),
(815, '66662222', 'CABRERA DE RAMIREZ TERESA', 'MIGUEL GRAU S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:43:49', '2022-04-06 15:47:03', NULL),
(816, '55552222', 'DELGADO GONZALES ELOY', 'ANDRES RAZURI S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:48:29', '2022-04-06 15:48:29', NULL),
(817, '44442222', 'FLORES LLONTOP GABRIELA', 'MIGUEL GRAU S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:52:12', '2022-04-06 15:52:12', NULL),
(818, '33332222', 'GONZALES RODRIGUEZ JUAN', 'MIGUEL GRAU S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:53:58', '2022-04-06 15:53:58', NULL),
(819, '99993333', 'HEREDIA ARROYO YOLANDA', 'MIGUEL GRAU S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 15:56:40', '2022-04-06 15:56:40', NULL),
(820, '77771111', 'AGAPITO FARRO BALTAZARA', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:13:36', '2022-04-06 16:13:36', NULL),
(821, '88883333', 'AGAPITO PISFIL JULIO', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:24:50', '2022-04-06 16:24:50', NULL),
(822, '66663333', 'CARRASCO COPIA SANTOS NILMA', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:28:26', '2022-04-06 16:28:26', NULL),
(823, '44443333', 'CHAVEZ BURGA OSCAR', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:31:26', '2022-04-06 16:31:26', NULL),
(824, '55553333', 'FARRO MENDOZA MANUELA', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:34:35', '2022-04-06 16:34:35', NULL),
(825, '99001111', 'LLONTOP GONZALES MANUELA', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:38:42', '2022-04-06 16:38:42', NULL),
(826, '66668888', 'LLONTOP RODRIGUEZ LILIANA DEL ROCIO', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:50:54', '2022-04-06 16:50:54', NULL),
(827, '77775555', 'MARCELO YAUCE SANTOS', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:54:13', '2022-04-06 16:54:13', NULL),
(828, '88887777', 'NEIRA MORALES JUANA', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 16:58:10', '2022-04-06 16:58:10', NULL),
(829, '66664444', 'RODRIGUEZ AGAPITO MICAELA', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:00:52', '2022-04-06 17:00:52', NULL),
(830, '11117777', 'RODRIGUEZ CUSTODIO VIOLETA', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:02:51', '2022-04-06 17:02:51', NULL),
(831, '77773333', 'RODRIGUEZ CUSTODIO ROSITA', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:05:54', '2022-04-06 17:05:54', NULL),
(832, '88884444', 'VELASQUEZ GARCIA OSCAR', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:08:13', '2022-04-06 17:08:13', NULL),
(833, '33337777', 'VERGARA GAMONAL AUGUSTO', 'PALMO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:10:28', '2022-04-06 17:10:28', NULL),
(834, '77112233', 'CASTRO  VENEGAS  ZAIDA', 'ONCE FEBRERO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:15:10', '2022-04-06 17:15:10', NULL),
(835, '44556611', 'MALCA  IGNACIO SEGUNDO', 'ONCE FEBRERO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:18:13', '2022-04-06 17:18:13', NULL),
(836, '44668899', 'SENMACHE GONZALES MANUELA ESTHER', 'ONCE FEBRERO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:21:52', '2022-04-25 23:54:34', '2022-04-25 23:54:34'),
(837, '33778899', 'DIAZ LINARES WILDER', 'ARICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:30:11', '2022-04-06 17:30:11', NULL),
(838, '11668844', 'LLONTOP MECHAN ANGEL', 'ARICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:32:45', '2022-04-06 17:32:45', NULL),
(839, '22779944', 'MORALES FLORES MARIA', 'ARICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:34:44', '2022-04-06 17:34:44', NULL),
(840, '44886666', 'RAMIREAZ OLANO MARLON JAVIER', 'JOSE QUIÑONES S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:55:36', '2022-04-06 17:55:36', NULL),
(841, '66004433', 'GUEVARA UCHOFEN CESAR AUGUSTO', 'LETICIA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 17:59:42', '2022-04-06 17:59:42', NULL),
(842, '11669900', 'FLORES GONZALES DIANA YRIS', 'NUEVO CHOSICA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 18:03:24', '2022-04-06 18:03:24', NULL),
(843, '44665555', 'GIL RODRIGUEZ LUIS ALBERTO', 'SANTA ANITA S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 18:07:03', '2022-04-06 18:07:03', NULL),
(844, '66994433', 'CIEZA CAYOTOPA UBELSER', 'ALGARROBOS S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-06 18:10:35', '2022-04-06 18:10:35', NULL),
(845, '80435983', 'ARTEAGA JACOBO JUAN ALEX', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 10:35:38', '2022-04-07 10:51:18', NULL),
(846, '07884150', 'DELGADO GONZALES GRACIELA', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 10:43:40', '2022-04-07 10:52:15', NULL),
(847, '44964157', 'GONZALES CARBAJAL DORTY MARISOL', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 10:45:46', '2022-04-07 10:52:54', NULL),
(848, '41034352', 'GORMAS CARDENA DORIS EDITHA', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 10:47:59', '2022-04-07 10:53:14', NULL),
(849, '03677955', 'CHIRA SAVEEDRA JUAN ALBERTO', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 10:54:10', '2022-04-07 10:54:10', NULL),
(850, '71782059', 'PUSE HUACCHA SERGIO MANUEL', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 10:56:33', '2022-04-07 10:56:33', NULL),
(851, '43589808', 'RUIZ REYNA RUBITH', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 10:58:08', '2022-04-07 10:58:08', NULL),
(852, '16794194', 'DIAZ BECERRA HELI MAGALY', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 11:00:39', '2022-04-07 11:00:39', NULL),
(853, '11112223', 'DELGADO GONZALES GRACIELA', 'TRUJILLO S/N', '2000-01-01', 'VICTORIA', 'CHICLAYO', 'LAMBAYEQUE', 'jasschosica@gmail.com', '0000000', 1, NULL, '2022-04-07 11:43:22', '2022-04-07 11:43:22', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CONTRATO`
--

CREATE TABLE `CONTRATO` (
  `CTO_CODIGO` int NOT NULL,
  `CTO_FECHA_TRAMITE` date NOT NULL,
  `CTO_FECHA_INICIO` date DEFAULT NULL,
  `CTO_FECHA_ANULACION` date DEFAULT NULL,
  `CTO_FECHA_SUSPENCION` date DEFAULT NULL,
  `CTO_FECHA_RECONECCION` date DEFAULT NULL,
  `CTO_FECHA_INICIO_MANTENIMIENTO` date DEFAULT NULL,
  `CTO_FECHA_FIN_MANTENIMIENTO` date DEFAULT NULL,
  `CTO_OBSERVACION` varchar(300) COLLATE utf8_spanish_ci DEFAULT '',
  `CTO_ESTADO` int NOT NULL,
  `CTO_AGU_CAR_CNX` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_AGU_DTO_CNX` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_AGU_DTO_RED` int DEFAULT NULL,
  `CTO_AGU_FEC_INS` date DEFAULT NULL,
  `CTO_AGU_MAT_CNX` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_AGU_MAT_ABA` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_AGU_UBI_CAJ` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_AGU_MAT_CAJ` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_AGU_EST_CAJ` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_AGU_MAT_TAP` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_AGU_EST_TAP` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_CAR_CNX` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_TIP_CNX` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_FEC_INS` date DEFAULT NULL,
  `CTO_ALC_MAT_CNX` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_DTO_RED` int DEFAULT NULL,
  `CTO_ALC_DTO_CNX` int DEFAULT NULL,
  `CTO_ALC_UBI_CAJ` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_MAT_CAJ` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_EST_CAJ` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_DIM_CAJ` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_MAT_TAP` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_EST_TAP` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_ALC_MED_TAP` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CTO_CREATED` datetime NOT NULL,
  `CTO_UPDATED` datetime NOT NULL,
  `PRE_CODIGO` int NOT NULL,
  `TUP_CODIGO` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `CONTRATO`
--

INSERT INTO `CONTRATO` (`CTO_CODIGO`, `CTO_FECHA_TRAMITE`, `CTO_FECHA_INICIO`, `CTO_FECHA_ANULACION`, `CTO_FECHA_SUSPENCION`, `CTO_FECHA_RECONECCION`, `CTO_FECHA_INICIO_MANTENIMIENTO`, `CTO_FECHA_FIN_MANTENIMIENTO`, `CTO_OBSERVACION`, `CTO_ESTADO`, `CTO_AGU_CAR_CNX`, `CTO_AGU_DTO_CNX`, `CTO_AGU_DTO_RED`, `CTO_AGU_FEC_INS`, `CTO_AGU_MAT_CNX`, `CTO_AGU_MAT_ABA`, `CTO_AGU_UBI_CAJ`, `CTO_AGU_MAT_CAJ`, `CTO_AGU_EST_CAJ`, `CTO_AGU_MAT_TAP`, `CTO_AGU_EST_TAP`, `CTO_ALC_CAR_CNX`, `CTO_ALC_TIP_CNX`, `CTO_ALC_FEC_INS`, `CTO_ALC_MAT_CNX`, `CTO_ALC_DTO_RED`, `CTO_ALC_DTO_CNX`, `CTO_ALC_UBI_CAJ`, `CTO_ALC_MAT_CAJ`, `CTO_ALC_EST_CAJ`, `CTO_ALC_DIM_CAJ`, `CTO_ALC_MAT_TAP`, `CTO_ALC_EST_TAP`, `CTO_ALC_MED_TAP`, `CTO_CREATED`, `CTO_UPDATED`, `PRE_CODIGO`, `TUP_CODIGO`) VALUES
(1, '2022-02-23', '2022-02-23', '2022-03-22', NULL, NULL, NULL, NULL, '', 1, 'con caja', '1/2', 2, NULL, 'pvc', 'pvc', 'vereda', 'concreto', 'buena', 'concreto', 'buena', 'con caja', 'convencional', '2018-11-23', 'pvc', 8, 4, 'vereda', 'concreto', 'buena', '70x40 cm', 'concreto', 'buena', '62x32 cm', '2022-02-23 18:40:34', '2022-03-22 15:28:02', 117, 1),
(2, '2022-02-23', NULL, NULL, NULL, NULL, NULL, NULL, 'SIN CONEXION', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-02-23 18:42:37', '2022-02-23 18:42:37', 116, 1),
(3, '2022-02-23', '2022-02-23', NULL, NULL, NULL, NULL, NULL, '', 1, 'con caja', '1/2', 2, '1960-07-23', 'pvc', 'fierro', 'vereda', 'concreto', 'buena', 'concreto', 'buena', 'con caja', 'condominial', '2007-07-23', 'pvc', 4, 4, 'vereda', 'concreto', 'buena', '60x40 cm', 'concreto', 'buena', '54x34 cm', '2022-02-23 18:45:07', '2022-02-23 18:45:07', 115, 1),
(4, '2022-02-23', '2022-02-23', NULL, NULL, NULL, NULL, NULL, '', 1, 'con caja', '1/2', 2, '1970-06-23', 'pvc', 'pvc', 'vereda', 'concreto', 'buena', 'concreto', 'buena', 'con caja', 'condominial', '2007-11-23', 'pvc', 4, 4, 'vereda', 'concreto', 'buena', '60x40 cm', 'concreto', 'buena', '54x34 cm', '2022-02-23 18:47:53', '2022-02-23 18:47:53', 114, 1),
(5, '2022-02-23', '2022-02-23', NULL, NULL, NULL, NULL, NULL, '', 1, 'con caja', '1/2', 2, '1970-07-23', 'pvc', 'pvc', 'vereda', 'concreto', 'buena', 'concreto', 'buena', 'con caja', 'condominial', '2007-11-23', 'pvc', 4, 4, 'vereda', 'concreto', 'buena', '60x40 cm', 'concreto', 'buena', '54x34 cm', '2022-02-23 18:50:08', '2022-02-23 18:50:08', 113, 1),
(6, '2022-02-23', '2022-02-23', NULL, NULL, NULL, NULL, NULL, '', 1, 'con caja', '1/2', 2, '1970-07-23', 'pvc', NULL, 'vereda', 'concreto', 'buena', 'concreto', 'buena', 'con caja', 'convencional', '2018-11-23', 'pvc', 8, 4, 'vereda', 'concreto', 'buena', '70x40 cm', 'concreto', 'buena', '62x32 cm', '2022-02-23 18:52:13', '2022-02-23 18:52:13', 112, 1),
(7, '2022-02-23', '2000-01-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-02-23 18:52:41', '2022-04-22 17:55:16', 111, 1),
(8, '2022-03-23', '2022-03-23', NULL, NULL, NULL, NULL, NULL, 'TIENE EL SERVICIO INSTALADO', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-23 21:59:14', '2022-03-23 21:59:14', 118, 1),
(9, '2022-03-24', '2022-03-24', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 00:57:05', '2022-03-24 00:57:05', 1, 40),
(10, '2022-03-24', '2022-03-24', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 01:13:49', '2022-03-24 01:13:49', 2, 1),
(11, '2022-03-24', '2022-03-24', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 01:42:08', '2022-03-24 01:42:08', 6, 1),
(12, '2022-03-24', '2022-03-24', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 01:46:46', '2022-03-24 01:46:46', 7, 1),
(13, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:20:16', '2022-03-29 01:20:16', 9, 1),
(14, '2022-03-29', '2020-10-19', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:21:52', '2022-03-29 01:23:19', 10, 1),
(15, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:24:52', '2022-03-29 01:24:52', 12, 1),
(16, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:26:15', '2022-03-29 01:26:15', 13, 1),
(17, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:27:50', '2022-03-29 01:27:50', 14, 1),
(18, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:29:19', '2022-03-29 01:29:19', 15, 1),
(19, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:30:59', '2022-03-29 01:30:59', 55, 1),
(20, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:32:29', '2022-03-29 01:32:29', 16, 1),
(21, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:36:32', '2022-03-29 01:36:32', 17, 1),
(22, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:37:56', '2022-03-29 01:37:56', 18, 1),
(23, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:39:31', '2022-03-29 01:39:31', 19, 1),
(24, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:40:37', '2022-03-29 01:40:37', 20, 1),
(25, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:47:07', '2022-03-29 01:47:07', 22, 1),
(26, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:49:08', '2022-03-29 01:49:08', 23, 1),
(27, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:51:33', '2022-03-29 01:51:33', 24, 1),
(28, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:53:01', '2022-03-29 01:53:01', 25, 1),
(29, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 01:57:01', '2022-03-29 01:57:01', 26, 40),
(30, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:02:01', '2022-03-29 02:02:01', 27, 1),
(31, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:03:31', '2022-03-29 02:03:31', 28, 1),
(32, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:05:31', '2022-03-29 02:05:31', 29, 1),
(33, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:07:52', '2022-03-29 02:07:52', 30, 40),
(34, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:12:17', '2022-03-29 02:12:17', 31, 1),
(35, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:13:17', '2022-03-29 02:13:17', 33, 1),
(36, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:14:15', '2022-03-29 02:14:15', 34, 1),
(37, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:17:13', '2022-03-29 02:17:13', 36, 1),
(38, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:20:10', '2022-03-29 02:20:10', 37, 1),
(39, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:22:24', '2022-03-29 02:22:24', 39, 1),
(40, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:23:10', '2022-03-29 02:23:10', 40, 1),
(41, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:24:33', '2022-03-29 02:24:33', 41, 1),
(42, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:25:58', '2022-03-29 02:25:58', 42, 1),
(43, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:26:44', '2022-03-29 02:26:44', 43, 1),
(44, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:28:54', '2022-03-29 02:28:54', 46, 1),
(45, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:30:04', '2022-03-29 02:30:04', 47, 1),
(46, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:04:34', '2022-03-29 03:04:34', 48, 1),
(47, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:05:25', '2022-03-29 03:05:25', 49, 1),
(48, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:06:59', '2022-03-29 03:06:59', 51, 1),
(49, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:08:01', '2022-03-29 03:08:01', 52, 1),
(50, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:09:16', '2022-03-29 03:09:16', 53, 1),
(51, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:10:45', '2022-03-29 03:10:45', 54, 1),
(52, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:12:15', '2022-03-29 03:12:15', 57, 1),
(53, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:13:03', '2022-03-29 03:13:03', 59, 1),
(54, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:14:06', '2022-03-29 03:14:06', 60, 1),
(55, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:15:36', '2022-03-29 03:15:36', 61, 1),
(56, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:16:27', '2022-03-29 03:16:27', 62, 1),
(57, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:17:55', '2022-03-29 03:17:55', 63, 1),
(58, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:19:04', '2022-03-29 03:19:04', 64, 1),
(59, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:23:09', '2022-03-29 03:23:09', 66, 1),
(60, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:25:15', '2022-03-29 03:25:15', 67, 1),
(61, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:26:38', '2022-03-29 03:26:38', 68, 1),
(62, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:27:52', '2022-03-29 03:27:52', 69, 1),
(63, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:29:30', '2022-03-29 03:29:30', 70, 1),
(64, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:30:41', '2022-03-29 03:30:41', 71, 1),
(65, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:32:13', '2022-03-29 03:32:13', 72, 1),
(66, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:33:51', '2022-03-29 03:33:51', 73, 1),
(67, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:39:00', '2022-03-29 03:39:00', 74, 1),
(68, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:39:56', '2022-03-29 03:39:56', 75, 1),
(69, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:44:55', '2022-03-29 03:44:55', 76, 1),
(70, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:46:26', '2022-03-29 03:46:26', 78, 1),
(71, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:47:28', '2022-03-29 03:47:28', 79, 1),
(72, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:48:32', '2022-03-29 03:48:32', 80, 1),
(73, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:49:35', '2022-03-29 03:49:35', 81, 1),
(74, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:50:43', '2022-03-29 03:50:43', 82, 40),
(75, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:52:24', '2022-03-29 03:52:24', 83, 1),
(76, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:53:52', '2022-03-29 03:53:52', 84, 1),
(77, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:55:47', '2022-03-29 03:55:47', 85, 1),
(78, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:56:41', '2022-03-29 03:56:41', 86, 1),
(79, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 03:59:52', '2022-03-29 03:59:52', 87, 1),
(80, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:01:08', '2022-03-29 04:01:08', 88, 1),
(81, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:02:41', '2022-03-29 04:02:41', 89, 1),
(82, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:03:50', '2022-03-29 04:03:50', 90, 1),
(83, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:04:55', '2022-03-29 04:04:55', 91, 1),
(84, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:05:49', '2022-03-29 04:05:49', 92, 1),
(85, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:06:49', '2022-03-29 04:06:49', 93, 1),
(86, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:07:56', '2022-03-29 04:07:56', 94, 1),
(87, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:08:56', '2022-03-29 04:08:56', 95, 1),
(88, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:10:27', '2022-03-29 04:10:27', 96, 1),
(89, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:11:17', '2022-03-29 04:11:17', 97, 1),
(90, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:12:30', '2022-03-29 04:12:30', 98, 1),
(91, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:13:35', '2022-03-29 04:13:35', 99, 1),
(92, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:17:02', '2022-03-29 04:17:02', 100, 1),
(93, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:17:56', '2022-03-29 04:17:56', 101, 1),
(94, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:19:27', '2022-03-29 04:19:27', 102, 1),
(95, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:20:05', '2022-03-29 04:20:05', 103, 1),
(96, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:20:47', '2022-03-29 04:20:47', 104, 1),
(97, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:21:40', '2022-03-29 04:21:40', 105, 1),
(98, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:23:20', '2022-03-29 04:23:20', 106, 1),
(99, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:25:35', '2022-03-29 04:25:35', 107, 1),
(100, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 04:27:35', '2022-03-29 04:27:35', 109, 1),
(101, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 22:55:31', '2022-03-29 22:55:31', 58, 1),
(102, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 22:59:30', '2022-03-29 22:59:30', 11, 46),
(103, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:03:44', '2022-03-29 23:03:44', 119, 1),
(104, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:06:57', '2022-03-29 23:06:57', 120, 1),
(105, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:10:31', '2022-03-29 23:10:31', 121, 1),
(106, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:13:38', '2022-03-29 23:13:38', 122, 1),
(107, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:16:27', '2022-03-29 23:16:27', 123, 1),
(108, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:25:22', '2022-03-29 23:25:22', 21, 1),
(109, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:29:29', '2022-03-29 23:29:29', 125, 1),
(110, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:32:04', '2022-03-29 23:32:04', 126, 1),
(111, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:33:16', '2022-03-29 23:33:16', 44, 1),
(112, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:34:00', '2022-03-29 23:34:00', 45, 1),
(113, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:39:23', '2022-03-29 23:39:23', 128, 1),
(114, '2022-03-29', '2022-03-29', NULL, NULL, NULL, '2022-04-20', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:43:53', '2022-04-20 23:24:35', 129, 1),
(115, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:48:02', '2022-03-29 23:48:02', 130, 1),
(116, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:53:26', '2022-03-29 23:53:26', 131, 1),
(117, '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:58:48', '2022-03-29 23:58:48', 132, 40),
(118, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:02:43', '2022-03-30 00:02:43', 32, 1),
(119, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:12:39', '2022-03-30 00:12:39', 133, 44),
(120, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:17:56', '2022-03-30 00:17:56', 8, 44),
(121, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:19:24', '2022-03-30 00:19:24', 35, 1),
(122, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:23:29', '2022-03-30 00:23:29', 134, 1),
(123, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:27:45', '2022-03-30 00:27:45', 135, 1),
(124, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:31:35', '2022-03-30 00:31:35', 136, 1),
(125, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:32:55', '2022-03-30 00:32:55', 38, 1),
(126, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:40:14', '2022-03-30 00:40:14', 138, 1),
(127, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:43:26', '2022-03-30 00:43:26', 139, 1),
(128, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:49:58', '2022-03-30 00:49:58', 140, 1),
(129, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:52:56', '2022-03-30 00:52:56', 141, 1),
(130, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:55:22', '2022-03-30 00:55:22', 142, 1),
(131, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:58:19', '2022-03-30 00:58:19', 143, 1),
(132, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 00:59:21', '2022-03-30 00:59:21', 50, 1),
(133, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:04:38', '2022-03-30 01:04:38', 144, 43),
(134, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:15:11', '2022-03-30 01:15:11', 147, 43),
(135, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:17:30', '2022-03-30 01:17:30', 65, 41),
(136, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:21:28', '2022-03-30 01:21:28', 148, 1),
(137, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:28:12', '2022-03-30 01:28:12', 150, 1),
(138, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:32:07', '2022-03-30 01:32:07', 151, 1),
(139, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:38:32', '2022-03-30 01:38:32', 152, 1),
(140, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:42:05', '2022-03-30 01:42:05', 153, 1),
(141, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:45:44', '2022-03-30 01:45:44', 154, 1),
(142, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:48:36', '2022-03-30 01:48:36', 155, 1),
(143, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 01:57:37', '2022-03-30 01:57:37', 156, 1),
(144, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:01:00', '2022-03-30 02:01:00', 157, 40),
(145, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:06:11', '2022-03-30 02:06:11', 158, 1),
(146, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:07:29', '2022-03-30 02:07:29', 77, 1),
(147, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:11:13', '2022-03-30 02:11:13', 159, 1),
(148, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:20:43', '2022-03-30 02:20:43', 161, 47),
(149, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:23:24', '2022-03-30 02:23:24', 162, 1),
(150, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:27:06', '2022-03-30 02:27:06', 163, 1),
(151, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:30:58', '2022-03-30 02:30:58', 164, 1),
(152, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:34:03', '2022-03-30 02:34:03', 165, 46),
(153, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:36:58', '2022-03-30 02:36:58', 166, 1),
(154, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:40:09', '2022-03-30 02:40:09', 167, 1),
(155, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:42:59', '2022-03-30 02:42:59', 168, 1),
(156, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:45:33', '2022-03-30 02:45:33', 169, 1),
(157, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:48:17', '2022-03-30 02:48:17', 170, 1),
(158, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:51:03', '2022-03-30 02:51:03', 171, 1),
(159, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:53:49', '2022-03-30 02:53:49', 172, 40),
(160, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:57:20', '2022-03-30 02:57:20', 173, 46),
(161, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 02:59:38', '2022-03-30 02:59:38', 174, 40),
(162, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 03:02:08', '2022-03-30 03:02:08', 175, 46),
(163, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 03:07:10', '2022-03-30 03:07:10', 177, 1),
(164, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 03:09:22', '2022-03-30 03:09:22', 108, 46),
(165, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 03:10:11', '2022-03-30 03:10:11', 110, 46),
(166, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 03:15:24', '2022-03-30 03:15:24', 178, 1),
(167, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 10:09:53', '2022-03-30 10:09:53', 3, 1),
(168, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 10:17:48', '2022-03-30 10:17:48', 4, 1),
(169, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 10:27:01', '2022-03-30 10:27:01', 179, 1),
(170, '2022-03-30', '2022-03-30', NULL, NULL, NULL, '2022-04-07', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 10:31:26', '2022-04-07 13:02:06', 5, 1),
(171, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 10:36:40', '2022-03-30 10:36:40', 180, 1),
(172, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 10:43:41', '2022-03-30 10:43:41', 181, 1),
(173, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 10:56:10', '2022-03-30 10:56:10', 182, 1),
(174, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:00:02', '2022-03-30 11:00:02', 183, 1),
(175, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:04:17', '2022-03-30 11:04:17', 184, 1),
(176, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:13:28', '2022-03-30 11:13:28', 185, 1),
(177, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:16:26', '2022-03-30 11:16:26', 186, 1),
(178, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:21:18', '2022-03-30 11:21:18', 187, 1),
(179, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:28:19', '2022-03-30 11:28:19', 188, 1),
(180, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:31:37', '2022-03-30 11:31:37', 189, 1),
(181, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:36:02', '2022-03-30 11:36:02', 190, 1),
(182, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:42:51', '2022-03-30 11:42:51', 191, 1);
INSERT INTO `CONTRATO` (`CTO_CODIGO`, `CTO_FECHA_TRAMITE`, `CTO_FECHA_INICIO`, `CTO_FECHA_ANULACION`, `CTO_FECHA_SUSPENCION`, `CTO_FECHA_RECONECCION`, `CTO_FECHA_INICIO_MANTENIMIENTO`, `CTO_FECHA_FIN_MANTENIMIENTO`, `CTO_OBSERVACION`, `CTO_ESTADO`, `CTO_AGU_CAR_CNX`, `CTO_AGU_DTO_CNX`, `CTO_AGU_DTO_RED`, `CTO_AGU_FEC_INS`, `CTO_AGU_MAT_CNX`, `CTO_AGU_MAT_ABA`, `CTO_AGU_UBI_CAJ`, `CTO_AGU_MAT_CAJ`, `CTO_AGU_EST_CAJ`, `CTO_AGU_MAT_TAP`, `CTO_AGU_EST_TAP`, `CTO_ALC_CAR_CNX`, `CTO_ALC_TIP_CNX`, `CTO_ALC_FEC_INS`, `CTO_ALC_MAT_CNX`, `CTO_ALC_DTO_RED`, `CTO_ALC_DTO_CNX`, `CTO_ALC_UBI_CAJ`, `CTO_ALC_MAT_CAJ`, `CTO_ALC_EST_CAJ`, `CTO_ALC_DIM_CAJ`, `CTO_ALC_MAT_TAP`, `CTO_ALC_EST_TAP`, `CTO_ALC_MED_TAP`, `CTO_CREATED`, `CTO_UPDATED`, `PRE_CODIGO`, `TUP_CODIGO`) VALUES
(183, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:47:12', '2022-03-30 11:47:12', 192, 1),
(184, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:50:54', '2022-03-30 11:50:54', 193, 1),
(185, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 11:56:41', '2022-03-30 11:56:41', 194, 1),
(186, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:02:50', '2022-03-30 12:02:50', 195, 1),
(187, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:08:37', '2022-03-30 12:08:37', 196, 1),
(188, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:13:43', '2022-03-30 12:13:43', 197, 1),
(189, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:17:55', '2022-03-30 12:17:55', 198, 1),
(190, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:22:11', '2022-03-30 12:22:11', 199, 1),
(191, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:27:49', '2022-03-30 12:27:49', 200, 1),
(192, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:34:21', '2022-03-30 12:34:21', 201, 1),
(193, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:39:08', '2022-03-30 12:39:08', 202, 1),
(194, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:44:13', '2022-03-30 12:44:13', 203, 1),
(195, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:48:18', '2022-03-30 12:48:18', 204, 1),
(196, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:51:23', '2022-03-30 12:51:23', 205, 1),
(197, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 12:56:56', '2022-03-30 12:56:56', 206, 1),
(198, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 13:34:03', '2022-03-30 13:34:03', 207, 1),
(199, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 13:38:22', '2022-03-30 13:38:22', 208, 1),
(200, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 13:42:50', '2022-03-30 13:42:50', 209, 1),
(201, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 13:49:24', '2022-03-30 13:49:24', 210, 1),
(202, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 13:54:18', '2022-03-30 13:54:18', 211, 1),
(203, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:01:30', '2022-03-30 14:01:30', 212, 1),
(204, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:04:26', '2022-03-30 14:04:26', 213, 1),
(205, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:09:31', '2022-03-30 14:09:31', 214, 1),
(206, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:14:27', '2022-03-30 14:14:27', 215, 1),
(207, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:18:03', '2022-03-30 14:18:03', 216, 1),
(208, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:22:15', '2022-03-30 14:22:15', 217, 1),
(209, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:27:20', '2022-03-30 14:27:20', 218, 1),
(210, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:41:19', '2022-03-30 14:41:19', 219, 1),
(211, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:48:30', '2022-03-30 14:48:30', 220, 1),
(212, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:53:35', '2022-03-30 14:53:35', 221, 1),
(213, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 14:58:15', '2022-03-30 14:58:15', 222, 1),
(214, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:03:08', '2022-03-30 15:03:08', 223, 1),
(215, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:08:45', '2022-03-30 15:08:45', 224, 1),
(216, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:14:06', '2022-03-30 15:14:06', 225, 1),
(217, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:18:10', '2022-03-30 15:18:10', 226, 1),
(218, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:22:36', '2022-03-30 15:22:36', 227, 1),
(219, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:33:15', '2022-03-30 15:33:15', 228, 1),
(220, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:37:30', '2022-03-30 15:37:30', 229, 1),
(221, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:40:45', '2022-03-30 15:40:45', 230, 1),
(222, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:45:02', '2022-03-30 15:45:02', 231, 1),
(223, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:49:09', '2022-03-30 15:49:09', 232, 1),
(224, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:52:31', '2022-03-30 15:52:31', 233, 1),
(225, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 15:59:17', '2022-03-30 15:59:17', 234, 1),
(226, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:05:13', '2022-03-30 16:05:13', 235, 1),
(227, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:10:03', '2022-03-30 16:10:03', 236, 1),
(228, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:16:17', '2022-03-30 16:16:17', 237, 1),
(229, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:20:51', '2022-03-30 16:20:51', 238, 1),
(230, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:34:27', '2022-03-30 16:34:27', 239, 1),
(231, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:40:12', '2022-03-30 16:40:12', 240, 1),
(232, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:43:53', '2022-03-30 16:43:53', 241, 1),
(233, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:50:44', '2022-03-30 16:50:44', 242, 1),
(234, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 16:55:50', '2022-03-30 16:55:50', 243, 1),
(235, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 17:00:44', '2022-03-30 17:00:44', 244, 1),
(236, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 17:04:29', '2022-03-30 17:04:29', 245, 1),
(237, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 17:09:25', '2022-03-30 17:09:25', 246, 1),
(238, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 17:13:19', '2022-03-30 17:13:19', 247, 1),
(239, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 17:16:50', '2022-03-30 17:16:50', 248, 1),
(240, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 17:21:35', '2022-03-30 17:21:35', 249, 1),
(241, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 17:26:26', '2022-03-30 17:26:26', 250, 1),
(242, '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-30 17:31:13', '2022-03-30 17:31:13', 251, 1),
(243, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 09:45:01', '2022-03-31 09:45:01', 252, 1),
(244, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 09:51:47', '2022-03-31 09:51:47', 253, 1),
(245, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 09:56:04', '2022-03-31 09:56:04', 254, 1),
(246, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:03:41', '2022-03-31 10:03:41', 255, 1),
(247, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:08:47', '2022-03-31 10:08:47', 256, 1),
(248, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:22:34', '2022-03-31 10:22:34', 257, 1),
(249, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:34:43', '2022-03-31 10:34:43', 258, 1),
(250, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:42:57', '2022-03-31 10:42:57', 259, 1),
(251, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:50:09', '2022-03-31 10:50:09', 260, 1),
(252, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:53:44', '2022-03-31 10:53:44', 261, 1),
(253, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:57:21', '2022-03-31 10:57:21', 262, 1),
(254, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:00:49', '2022-03-31 11:00:49', 263, 1),
(255, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:05:19', '2022-03-31 11:05:19', 264, 1),
(256, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:10:26', '2022-03-31 11:10:26', 265, 1),
(257, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:13:28', '2022-03-31 11:13:28', 266, 1),
(258, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:16:27', '2022-03-31 11:16:27', 267, 1),
(259, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:20:41', '2022-03-31 11:20:41', 268, 1),
(260, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:26:40', '2022-03-31 11:26:40', 269, 1),
(261, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:29:05', '2022-03-31 11:29:05', 270, 1),
(262, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:34:05', '2022-03-31 11:34:05', 271, 1),
(263, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:37:18', '2022-03-31 11:37:18', 272, 1),
(264, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:40:19', '2022-03-31 11:40:19', 273, 1),
(265, '2022-03-31', '2022-03-31', NULL, NULL, NULL, '2022-04-07', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:47:04', '2022-04-07 13:10:39', 274, 1),
(266, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:50:01', '2022-03-31 11:50:01', 275, 1),
(267, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:52:38', '2022-03-31 11:52:38', 276, 1),
(268, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:54:54', '2022-03-31 11:54:54', 277, 1),
(269, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 11:57:46', '2022-03-31 11:57:46', 278, 1),
(270, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:17:26', '2022-03-31 12:17:26', 279, 1),
(271, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:22:59', '2022-03-31 12:22:59', 280, 1),
(272, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:28:09', '2022-03-31 12:28:09', 281, 1),
(273, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:45:43', '2022-03-31 12:45:43', 282, 1),
(274, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:49:30', '2022-03-31 12:49:30', 284, 1),
(275, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:53:29', '2022-03-31 12:53:29', 285, 1),
(276, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 13:08:36', '2022-03-31 13:08:36', 286, 1),
(277, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 13:43:08', '2022-03-31 13:43:08', 287, 1),
(278, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 13:55:18', '2022-03-31 13:55:18', 288, 1),
(279, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 13:58:47', '2022-03-31 13:58:47', 289, 40),
(280, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:01:44', '2022-03-31 14:01:44', 290, 1),
(281, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:05:59', '2022-03-31 14:05:59', 291, 1),
(282, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:40:25', '2022-03-31 14:40:25', 283, 1),
(283, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:47:11', '2022-03-31 14:47:11', 292, 1),
(284, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:51:04', '2022-03-31 14:51:04', 293, 1),
(285, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:56:47', '2022-03-31 14:56:47', 294, 1),
(286, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:00:46', '2022-03-31 15:00:46', 295, 1),
(287, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:04:27', '2022-03-31 15:04:27', 296, 1),
(288, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:09:10', '2022-03-31 15:09:10', 297, 1),
(289, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:15:04', '2022-03-31 15:15:04', 298, 1),
(290, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:19:32', '2022-03-31 15:19:32', 299, 1),
(291, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:23:38', '2022-03-31 15:23:38', 300, 1),
(292, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:26:40', '2022-03-31 15:26:40', 301, 1),
(293, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:30:38', '2022-03-31 15:30:38', 302, 1),
(294, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:36:07', '2022-03-31 15:36:07', 303, 1),
(295, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:39:18', '2022-03-31 15:39:18', 304, 1),
(296, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:42:16', '2022-03-31 15:42:16', 305, 1),
(297, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:45:12', '2022-03-31 15:45:12', 306, 1),
(298, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:49:11', '2022-03-31 15:49:11', 307, 1),
(299, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:00:38', '2022-03-31 16:00:38', 308, 1),
(300, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:05:51', '2022-03-31 16:05:51', 309, 1),
(301, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:09:23', '2022-03-31 16:09:23', 310, 1),
(302, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:15:59', '2022-03-31 16:15:59', 311, 1),
(303, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:18:13', '2022-03-31 16:18:13', 312, 1),
(304, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:36:04', '2022-03-31 16:36:04', 313, 1),
(305, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:40:00', '2022-03-31 16:40:00', 314, 1),
(306, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:47:13', '2022-03-31 16:47:13', 315, 1),
(307, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:51:08', '2022-03-31 16:51:08', 316, 1),
(308, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:54:47', '2022-03-31 16:54:47', 317, 1),
(309, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:58:24', '2022-03-31 16:58:24', 318, 1),
(310, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:04:12', '2022-03-31 17:04:12', 319, 1),
(311, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:08:11', '2022-03-31 17:08:11', 320, 1),
(312, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:22:41', '2022-03-31 17:22:41', 321, 1),
(313, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:24:21', '2022-03-31 17:24:21', 322, 1),
(314, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:32:47', '2022-03-31 17:32:47', 323, 1),
(315, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:35:45', '2022-03-31 17:35:45', 324, 1),
(316, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:42:51', '2022-03-31 17:42:51', 325, 1),
(317, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:47:29', '2022-03-31 17:47:29', 326, 1),
(318, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:52:53', '2022-03-31 17:52:53', 327, 1),
(319, '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:57:36', '2022-03-31 17:57:36', 328, 1),
(320, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 09:46:59', '2022-04-01 09:46:59', 329, 1),
(321, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 09:52:56', '2022-04-01 09:52:56', 330, 1),
(322, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 09:59:43', '2022-04-01 09:59:43', 331, 1),
(323, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:03:48', '2022-04-01 10:03:48', 332, 1),
(324, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:09:08', '2022-04-01 10:09:08', 333, 1),
(325, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:13:37', '2022-04-01 10:13:37', 334, 1),
(326, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:18:12', '2022-04-01 10:18:12', 335, 1),
(327, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:20:56', '2022-04-01 10:20:56', 336, 1),
(328, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:24:54', '2022-04-01 10:24:54', 337, 1),
(329, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:31:35', '2022-04-01 10:31:35', 338, 1),
(330, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:35:39', '2022-04-01 10:35:39', 339, 1),
(331, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:38:16', '2022-04-01 10:38:16', 340, 1),
(332, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:42:01', '2022-04-01 10:42:01', 341, 1),
(333, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:46:10', '2022-04-01 10:46:10', 342, 1),
(334, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:50:18', '2022-04-01 10:50:18', 343, 1),
(335, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:54:23', '2022-04-01 10:54:23', 344, 1),
(336, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:58:28', '2022-04-01 10:58:28', 345, 1),
(337, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:01:38', '2022-04-01 11:01:38', 346, 1),
(338, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:05:05', '2022-04-01 11:05:05', 347, 1),
(339, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:13:28', '2022-04-01 11:13:28', 348, 1),
(340, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:17:26', '2022-04-01 11:17:26', 349, 1),
(341, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:21:39', '2022-04-01 11:21:39', 350, 1),
(342, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:24:37', '2022-04-01 11:24:37', 351, 1),
(343, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:28:52', '2022-04-01 11:28:52', 352, 1),
(344, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:33:51', '2022-04-01 11:33:51', 353, 1),
(345, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:37:58', '2022-04-01 11:37:58', 354, 1),
(346, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:45:52', '2022-04-01 11:45:52', 355, 1),
(347, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:51:33', '2022-04-01 11:51:33', 356, 1),
(348, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:55:22', '2022-04-01 11:55:22', 357, 1),
(349, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:00:09', '2022-04-01 12:00:09', 358, 1),
(350, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:04:00', '2022-04-01 12:04:00', 359, 1),
(351, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:11:40', '2022-04-01 12:11:40', 360, 1),
(352, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:15:56', '2022-04-01 12:15:56', 361, 1),
(353, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:21:15', '2022-04-01 12:21:15', 362, 1),
(354, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:25:37', '2022-04-01 12:25:37', 363, 1),
(355, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:28:44', '2022-04-01 12:28:44', 364, 1),
(356, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:31:18', '2022-04-01 12:31:18', 365, 1),
(357, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:34:50', '2022-04-01 12:34:50', 366, 1),
(358, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:41:35', '2022-04-01 12:41:35', 367, 1),
(359, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:44:19', '2022-04-01 12:44:19', 368, 1),
(360, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:49:29', '2022-04-01 12:49:29', 369, 1),
(361, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:52:46', '2022-04-01 12:52:46', 370, 1),
(362, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:55:38', '2022-04-01 12:55:38', 371, 1),
(363, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:59:14', '2022-04-01 12:59:14', 372, 1),
(364, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:04:42', '2022-04-01 13:04:42', 373, 1),
(365, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:08:54', '2022-04-01 13:08:54', 374, 1);
INSERT INTO `CONTRATO` (`CTO_CODIGO`, `CTO_FECHA_TRAMITE`, `CTO_FECHA_INICIO`, `CTO_FECHA_ANULACION`, `CTO_FECHA_SUSPENCION`, `CTO_FECHA_RECONECCION`, `CTO_FECHA_INICIO_MANTENIMIENTO`, `CTO_FECHA_FIN_MANTENIMIENTO`, `CTO_OBSERVACION`, `CTO_ESTADO`, `CTO_AGU_CAR_CNX`, `CTO_AGU_DTO_CNX`, `CTO_AGU_DTO_RED`, `CTO_AGU_FEC_INS`, `CTO_AGU_MAT_CNX`, `CTO_AGU_MAT_ABA`, `CTO_AGU_UBI_CAJ`, `CTO_AGU_MAT_CAJ`, `CTO_AGU_EST_CAJ`, `CTO_AGU_MAT_TAP`, `CTO_AGU_EST_TAP`, `CTO_ALC_CAR_CNX`, `CTO_ALC_TIP_CNX`, `CTO_ALC_FEC_INS`, `CTO_ALC_MAT_CNX`, `CTO_ALC_DTO_RED`, `CTO_ALC_DTO_CNX`, `CTO_ALC_UBI_CAJ`, `CTO_ALC_MAT_CAJ`, `CTO_ALC_EST_CAJ`, `CTO_ALC_DIM_CAJ`, `CTO_ALC_MAT_TAP`, `CTO_ALC_EST_TAP`, `CTO_ALC_MED_TAP`, `CTO_CREATED`, `CTO_UPDATED`, `PRE_CODIGO`, `TUP_CODIGO`) VALUES
(366, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:16:13', '2022-04-01 13:16:13', 375, 1),
(367, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:19:57', '2022-04-01 13:19:57', 376, 1),
(368, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:23:09', '2022-04-01 13:23:09', 377, 1),
(369, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:27:38', '2022-04-01 13:27:38', 378, 1),
(370, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:31:13', '2022-04-01 13:31:13', 379, 1),
(371, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:03:20', '2022-04-01 14:03:20', 380, 1),
(372, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:05:40', '2022-04-01 14:05:40', 381, 1),
(373, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:11:19', '2022-04-01 14:11:19', 382, 1),
(374, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:15:47', '2022-04-01 14:15:47', 383, 1),
(375, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:18:26', '2022-04-01 14:18:26', 384, 1),
(376, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:21:48', '2022-04-01 14:21:48', 385, 1),
(377, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:25:16', '2022-04-01 14:25:16', 386, 1),
(378, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:30:57', '2022-04-01 14:30:57', 387, 1),
(379, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:34:07', '2022-04-01 14:34:07', 388, 1),
(380, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:36:55', '2022-04-01 14:36:55', 389, 1),
(381, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:40:17', '2022-04-01 14:40:17', 390, 1),
(382, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:42:50', '2022-04-01 14:42:50', 391, 1),
(383, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:45:23', '2022-04-01 14:45:23', 392, 1),
(384, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:48:18', '2022-04-01 14:48:18', 393, 1),
(385, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:50:29', '2022-04-01 14:50:29', 394, 1),
(386, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:54:55', '2022-04-01 14:54:55', 395, 1),
(387, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:57:33', '2022-04-01 14:57:33', 396, 1),
(388, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:59:44', '2022-04-01 14:59:44', 397, 1),
(389, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:02:26', '2022-04-01 15:02:26', 398, 1),
(390, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:44:06', '2022-04-01 15:44:06', 399, 1),
(391, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:49:56', '2022-04-01 15:49:56', 400, 1),
(392, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:53:54', '2022-04-01 15:53:54', 401, 1),
(393, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:57:17', '2022-04-01 15:57:17', 402, 1),
(394, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:01:04', '2022-04-01 16:01:04', 403, 1),
(395, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:04:31', '2022-04-01 16:04:31', 404, 1),
(396, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:38:33', '2022-04-01 16:38:33', 405, 1),
(397, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:42:23', '2022-04-01 16:42:23', 406, 1),
(398, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:46:06', '2022-04-01 16:46:06', 407, 1),
(399, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:51:40', '2022-04-01 16:51:40', 408, 1),
(400, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:54:46', '2022-04-01 16:54:46', 409, 1),
(401, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:57:45', '2022-04-01 16:57:45', 410, 1),
(402, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:01:13', '2022-04-01 17:01:13', 411, 1),
(403, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:05:29', '2022-04-01 17:05:29', 412, 1),
(404, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:15:40', '2022-04-01 17:15:40', 413, 1),
(405, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:18:40', '2022-04-01 17:18:40', 414, 1),
(406, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:21:14', '2022-04-01 17:21:14', 415, 1),
(407, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:24:02', '2022-04-01 17:24:02', 416, 1),
(408, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:27:28', '2022-04-01 17:27:28', 417, 1),
(409, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:32:32', '2022-04-01 17:32:32', 418, 1),
(410, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:37:11', '2022-04-01 17:37:11', 419, 1),
(411, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:41:32', '2022-04-01 17:41:32', 420, 1),
(412, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:48:00', '2022-04-01 17:48:00', 421, 1),
(413, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:52:49', '2022-04-01 17:52:49', 422, 1),
(414, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:54:53', '2022-04-01 17:54:53', 423, 1),
(415, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:57:06', '2022-04-01 17:57:06', 424, 1),
(416, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:03:27', '2022-04-01 18:03:27', 425, 1),
(417, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:05:16', '2022-04-01 18:05:16', 426, 1),
(418, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:06:50', '2022-04-01 18:06:50', 427, 1),
(419, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:09:17', '2022-04-01 18:09:17', 428, 1),
(420, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:11:04', '2022-04-01 18:11:04', 429, 1),
(421, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:12:50', '2022-04-01 18:12:50', 430, 1),
(422, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:15:42', '2022-04-01 18:15:42', 431, 1),
(423, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:17:34', '2022-04-01 18:17:34', 432, 1),
(424, '2022-04-01', '2022-04-01', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:19:12', '2022-04-01 18:19:12', 433, 1),
(425, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 15:57:56', '2022-04-03 15:57:56', 434, 1),
(426, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:02:26', '2022-04-03 16:02:26', 435, 1),
(427, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:07:27', '2022-04-03 16:07:27', 436, 1),
(428, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:13:37', '2022-04-03 16:13:37', 438, 1),
(429, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:20:42', '2022-04-03 16:20:42', 439, 1),
(430, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:26:49', '2022-04-03 16:26:49', 440, 1),
(431, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:33:02', '2022-04-03 16:33:02', 441, 1),
(432, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:35:43', '2022-04-03 16:35:43', 442, 1),
(433, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:39:44', '2022-04-03 16:39:44', 443, 1),
(434, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:49:58', '2022-04-03 16:49:58', 444, 1),
(435, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:17:35', '2022-04-03 17:17:35', 445, 1),
(436, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:22:45', '2022-04-03 17:22:45', 446, 1),
(437, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:24:48', '2022-04-03 17:24:48', 447, 1),
(438, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:27:47', '2022-04-03 17:27:47', 448, 1),
(439, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:31:39', '2022-04-03 17:31:39', 449, 1),
(440, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:34:59', '2022-04-03 17:34:59', 450, 1),
(441, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:40:26', '2022-04-03 17:40:26', 451, 1),
(442, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:52:19', '2022-04-03 17:52:19', 452, 1),
(443, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:56:20', '2022-04-03 17:56:20', 453, 1),
(444, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:59:16', '2022-04-03 17:59:16', 454, 1),
(445, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 18:13:32', '2022-04-03 18:13:32', 456, 1),
(446, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 18:19:04', '2022-04-03 18:19:04', 457, 1),
(447, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 18:21:50', '2022-04-03 18:21:50', 458, 1),
(448, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 18:50:09', '2022-04-03 18:50:09', 459, 1),
(449, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:02:13', '2022-04-03 19:02:13', 460, 1),
(450, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:10:37', '2022-04-03 19:10:37', 461, 40),
(451, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:14:03', '2022-04-03 19:14:03', 462, 40),
(452, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:17:05', '2022-04-03 19:17:05', 463, 1),
(453, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:20:26', '2022-04-03 19:20:26', 464, 1),
(454, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:26:25', '2022-04-03 19:26:25', 465, 1),
(455, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:57:26', '2022-04-03 19:57:26', 466, 1),
(456, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:00:08', '2022-04-03 20:00:08', 467, 1),
(457, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:03:04', '2022-04-03 20:03:04', 468, 1),
(458, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:15:53', '2022-04-03 20:15:53', 469, 1),
(459, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:20:48', '2022-04-03 20:20:48', 470, 1),
(460, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:23:37', '2022-04-03 20:23:37', 471, 1),
(461, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:28:05', '2022-04-03 20:28:05', 472, 1),
(462, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:29:04', '2022-04-03 20:29:04', 473, 1),
(463, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:32:40', '2022-04-03 20:32:40', 474, 1),
(464, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:35:24', '2022-04-03 20:35:24', 475, 1),
(465, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:38:17', '2022-04-03 20:38:17', 476, 1),
(466, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:43:14', '2022-04-03 20:43:14', 477, 1),
(467, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:49:37', '2022-04-03 20:49:37', 478, 1),
(468, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:56:58', '2022-04-03 20:56:58', 479, 1),
(469, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:00:47', '2022-04-03 21:00:47', 480, 1),
(470, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:03:36', '2022-04-03 21:03:36', 481, 1),
(471, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:06:18', '2022-04-03 21:06:18', 482, 1),
(472, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:08:47', '2022-04-03 21:08:47', 483, 1),
(473, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:11:49', '2022-04-03 21:11:49', 484, 1),
(474, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:15:43', '2022-04-03 21:15:43', 485, 1),
(475, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:18:03', '2022-04-03 21:18:03', 486, 1),
(476, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:20:08', '2022-04-03 21:20:08', 487, 1),
(477, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:45:06', '2022-04-03 21:45:06', 488, 1),
(478, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:49:07', '2022-04-03 21:49:07', 489, 1),
(479, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:22:43', '2022-04-03 22:22:43', 490, 1),
(480, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:25:57', '2022-04-03 22:25:57', 491, 1),
(481, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:29:12', '2022-04-03 22:29:12', 492, 40),
(482, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:34:50', '2022-04-03 22:34:50', 493, 1),
(483, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:38:21', '2022-04-03 22:38:21', 494, 1),
(484, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:45:09', '2022-04-03 22:45:09', 495, 1),
(485, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:49:30', '2022-04-03 22:49:30', 496, 1),
(486, '2022-04-03', '2022-04-03', NULL, NULL, NULL, '2022-04-03', '2022-04-03', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 23:26:13', '2022-04-03 23:33:00', 497, 1),
(487, '2022-04-03', '2022-04-03', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 23:56:50', '2022-04-03 23:56:50', 498, 1),
(488, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:00:12', '2022-04-04 00:00:12', 499, 1),
(489, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:04:05', '2022-04-04 00:04:05', 500, 1),
(490, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:06:50', '2022-04-04 00:06:50', 501, 1),
(491, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:10:01', '2022-04-04 00:10:01', 502, 1),
(492, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:12:47', '2022-04-04 00:12:47', 503, 1),
(493, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:15:32', '2022-04-04 00:15:32', 504, 1),
(494, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:21:40', '2022-04-04 00:21:40', 505, 1),
(495, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:24:53', '2022-04-04 00:24:53', 506, 1),
(496, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:28:41', '2022-04-04 00:28:41', 507, 1),
(497, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:31:55', '2022-04-04 00:31:55', 508, 1),
(498, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:35:01', '2022-04-04 00:35:01', 509, 1),
(499, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:38:02', '2022-04-04 00:38:02', 510, 1),
(500, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:42:17', '2022-04-04 00:42:17', 511, 1),
(501, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:45:01', '2022-04-04 00:45:01', 512, 1),
(502, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:47:07', '2022-04-04 00:47:07', 513, 1),
(503, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 15:56:25', '2022-04-04 15:56:25', 514, 1),
(504, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:02:52', '2022-04-04 16:02:52', 515, 1),
(505, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:11:56', '2022-04-04 16:11:56', 516, 1),
(506, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:19:14', '2022-04-04 16:19:14', 517, 1),
(507, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:25:03', '2022-04-04 16:25:03', 518, 1),
(508, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:30:27', '2022-04-04 16:30:27', 519, 1),
(509, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:44:55', '2022-04-04 16:44:55', 520, 40),
(510, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:49:20', '2022-04-04 16:49:20', 521, 1),
(511, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:54:28', '2022-04-04 16:54:28', 522, 1),
(512, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:59:32', '2022-04-04 16:59:32', 523, 1),
(513, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:02:46', '2022-04-04 17:02:46', 524, 1),
(514, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:10:02', '2022-04-04 17:10:02', 525, 1),
(515, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:22:25', '2022-04-04 17:22:25', 526, 1),
(516, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:25:07', '2022-04-04 17:25:07', 527, 1),
(517, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:29:01', '2022-04-04 17:29:01', 528, 1),
(518, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:31:57', '2022-04-04 17:31:57', 529, 1),
(519, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:35:01', '2022-04-04 17:35:01', 530, 1),
(520, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:38:55', '2022-04-04 17:38:55', 531, 1),
(521, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:42:14', '2022-04-04 17:42:14', 532, 1),
(522, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:46:21', '2022-04-04 17:46:21', 533, 1),
(523, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:55:16', '2022-04-04 17:55:16', 534, 1),
(524, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:59:34', '2022-04-04 17:59:34', 535, 1),
(525, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:02:56', '2022-04-04 18:02:56', 536, 1),
(526, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:05:44', '2022-04-04 18:05:44', 537, 1),
(527, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:09:00', '2022-04-04 18:09:00', 538, 1),
(528, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:12:26', '2022-04-04 18:12:26', 539, 1),
(529, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:16:07', '2022-04-04 18:16:07', 540, 1),
(530, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:19:21', '2022-04-04 18:19:21', 541, 1),
(531, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:21:36', '2022-04-04 18:21:36', 542, 1),
(532, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:24:46', '2022-04-04 18:24:46', 543, 1),
(533, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:27:17', '2022-04-04 18:27:17', 544, 1),
(534, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:29:43', '2022-04-04 18:29:43', 545, 1),
(535, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:46:30', '2022-04-04 18:46:30', 546, 1),
(536, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:50:46', '2022-04-04 18:50:46', 547, 1),
(537, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:53:32', '2022-04-04 18:53:32', 548, 1),
(538, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:58:02', '2022-04-04 18:58:02', 549, 1),
(539, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:01:36', '2022-04-04 19:01:36', 550, 1),
(540, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:09:55', '2022-04-04 19:09:55', 551, 1),
(541, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:13:32', '2022-04-04 19:13:32', 552, 1),
(542, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:17:04', '2022-04-04 19:17:04', 553, 1),
(543, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:20:09', '2022-04-04 19:20:09', 554, 1),
(544, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:23:47', '2022-04-04 19:23:47', 555, 1),
(545, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:27:32', '2022-04-04 19:27:32', 556, 1),
(546, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:31:14', '2022-04-04 19:31:14', 557, 1),
(547, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:36:56', '2022-04-04 19:36:56', 558, 1),
(548, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:39:49', '2022-04-04 19:39:49', 559, 1);
INSERT INTO `CONTRATO` (`CTO_CODIGO`, `CTO_FECHA_TRAMITE`, `CTO_FECHA_INICIO`, `CTO_FECHA_ANULACION`, `CTO_FECHA_SUSPENCION`, `CTO_FECHA_RECONECCION`, `CTO_FECHA_INICIO_MANTENIMIENTO`, `CTO_FECHA_FIN_MANTENIMIENTO`, `CTO_OBSERVACION`, `CTO_ESTADO`, `CTO_AGU_CAR_CNX`, `CTO_AGU_DTO_CNX`, `CTO_AGU_DTO_RED`, `CTO_AGU_FEC_INS`, `CTO_AGU_MAT_CNX`, `CTO_AGU_MAT_ABA`, `CTO_AGU_UBI_CAJ`, `CTO_AGU_MAT_CAJ`, `CTO_AGU_EST_CAJ`, `CTO_AGU_MAT_TAP`, `CTO_AGU_EST_TAP`, `CTO_ALC_CAR_CNX`, `CTO_ALC_TIP_CNX`, `CTO_ALC_FEC_INS`, `CTO_ALC_MAT_CNX`, `CTO_ALC_DTO_RED`, `CTO_ALC_DTO_CNX`, `CTO_ALC_UBI_CAJ`, `CTO_ALC_MAT_CAJ`, `CTO_ALC_EST_CAJ`, `CTO_ALC_DIM_CAJ`, `CTO_ALC_MAT_TAP`, `CTO_ALC_EST_TAP`, `CTO_ALC_MED_TAP`, `CTO_CREATED`, `CTO_UPDATED`, `PRE_CODIGO`, `TUP_CODIGO`) VALUES
(549, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:43:03', '2022-04-04 19:43:03', 560, 1),
(550, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:46:21', '2022-04-04 19:46:21', 561, 1),
(551, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:54:28', '2022-04-04 19:54:28', 562, 1),
(552, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:01:04', '2022-04-04 20:01:04', 563, 1),
(553, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:04:16', '2022-04-04 20:04:16', 564, 1),
(554, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:07:26', '2022-04-04 20:07:26', 565, 1),
(555, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:12:48', '2022-04-04 20:12:48', 566, 1),
(556, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:17:30', '2022-04-04 20:17:30', 567, 1),
(557, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:21:52', '2022-04-04 20:21:52', 568, 1),
(558, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:26:16', '2022-04-04 20:26:16', 569, 1),
(559, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:30:53', '2022-04-04 20:30:53', 570, 1),
(560, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:34:58', '2022-04-04 20:34:58', 571, 1),
(561, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:38:15', '2022-04-04 20:38:15', 572, 1),
(562, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:41:21', '2022-04-04 20:41:21', 573, 1),
(563, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:44:53', '2022-04-04 20:44:53', 574, 1),
(564, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:47:35', '2022-04-04 20:47:35', 575, 1),
(565, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 21:13:57', '2022-04-04 21:13:57', 576, 1),
(566, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 21:20:49', '2022-04-04 21:20:49', 577, 1),
(567, '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 21:24:54', '2022-04-04 21:24:54', 578, 1),
(568, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:05:46', '2022-04-05 00:05:46', 579, 1),
(569, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:14:31', '2022-04-05 00:14:31', 580, 1),
(570, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:24:32', '2022-04-05 00:24:32', 581, 1),
(571, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:33:25', '2022-04-05 00:33:25', 582, 1),
(572, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:43:18', '2022-04-05 00:43:18', 583, 1),
(573, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:48:16', '2022-04-05 00:48:16', 584, 1),
(574, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:56:40', '2022-04-05 00:56:40', 585, 1),
(575, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 01:06:06', '2022-04-05 01:06:06', 586, 1),
(576, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:20:52', '2022-04-05 15:20:52', 587, 1),
(577, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:24:02', '2022-04-05 15:24:02', 588, 1),
(578, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:32:50', '2022-04-05 15:32:50', 589, 1),
(579, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:43:22', '2022-04-05 15:43:22', 590, 1),
(580, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:47:56', '2022-04-05 15:47:56', 591, 1),
(581, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:52:18', '2022-04-05 15:52:18', 592, 1),
(582, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:57:19', '2022-04-05 15:57:19', 593, 1),
(583, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:01:55', '2022-04-05 16:01:55', 594, 1),
(584, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:07:32', '2022-04-05 16:07:32', 595, 1),
(585, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:18:02', '2022-04-05 16:18:02', 596, 1),
(586, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:25:26', '2022-04-05 16:25:26', 597, 1),
(587, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:29:17', '2022-04-05 16:29:17', 598, 1),
(588, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:12:17', '2022-04-05 17:12:17', 599, 1),
(589, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:18:43', '2022-04-05 17:18:43', 600, 1),
(590, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:21:46', '2022-04-05 17:21:46', 601, 1),
(591, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:24:38', '2022-04-05 17:24:38', 602, 1),
(592, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:27:51', '2022-04-05 17:27:51', 603, 1),
(593, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:31:55', '2022-04-05 17:31:55', 604, 1),
(594, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:36:35', '2022-04-05 17:36:35', 605, 1),
(595, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:39:07', '2022-04-05 17:39:07', 606, 1),
(596, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:43:32', '2022-04-05 17:43:32', 607, 1),
(597, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:47:37', '2022-04-05 17:47:37', 608, 1),
(598, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:51:00', '2022-04-05 17:51:00', 609, 1),
(599, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:53:56', '2022-04-05 17:53:56', 610, 1),
(600, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:58:24', '2022-04-05 17:58:24', 611, 1),
(601, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:04:32', '2022-04-05 18:04:32', 612, 1),
(602, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:14:51', '2022-04-05 18:14:51', 613, 1),
(603, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:18:23', '2022-04-05 18:18:23', 614, 1),
(604, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:20:59', '2022-04-05 18:20:59', 615, 1),
(605, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:25:29', '2022-04-05 18:25:29', 616, 1),
(606, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:32:07', '2022-04-05 18:32:07', 617, 1),
(607, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:38:01', '2022-04-05 18:38:01', 618, 1),
(608, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:42:47', '2022-04-05 18:42:47', 619, 1),
(609, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:50:09', '2022-04-05 18:50:09', 620, 1),
(610, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:52:46', '2022-04-05 18:52:46', 621, 1),
(611, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:55:49', '2022-04-05 18:55:49', 622, 1),
(612, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:58:54', '2022-04-05 18:58:54', 623, 1),
(613, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:04:07', '2022-04-05 19:04:07', 624, 1),
(614, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:11:24', '2022-04-05 19:11:24', 625, 1),
(615, '2022-04-05', NULL, NULL, NULL, NULL, NULL, NULL, '', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:14:52', '2022-04-05 19:14:52', 626, 1),
(616, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:21:37', '2022-04-05 19:21:37', 627, 1),
(617, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:24:39', '2022-04-05 19:24:39', 628, 1),
(618, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:41:28', '2022-04-05 19:41:28', 629, 1),
(619, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:11:58', '2022-04-05 20:11:58', 630, 1),
(620, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:16:21', '2022-04-05 20:16:21', 631, 1),
(621, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:19:59', '2022-04-05 20:19:59', 632, 1),
(622, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:23:01', '2022-04-05 20:23:01', 633, 1),
(623, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:25:50', '2022-04-05 20:25:50', 634, 1),
(624, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:53:31', '2022-04-05 20:53:31', 635, 1),
(625, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:58:04', '2022-04-05 20:58:04', 636, 1),
(626, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:01:09', '2022-04-05 21:01:09', 637, 1),
(627, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:06:07', '2022-04-05 21:06:07', 638, 1),
(628, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:09:12', '2022-04-05 21:09:12', 639, 1),
(629, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:12:48', '2022-04-05 21:12:48', 640, 1),
(630, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:16:46', '2022-04-05 21:16:46', 641, 1),
(631, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:22:03', '2022-04-05 21:22:03', 642, 1),
(632, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:27:53', '2022-04-05 21:27:53', 643, 1),
(633, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:32:52', '2022-04-05 21:32:52', 644, 1),
(634, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:35:27', '2022-04-05 21:35:27', 645, 1),
(635, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:38:40', '2022-04-05 21:38:40', 646, 1),
(636, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:42:07', '2022-04-05 21:42:07', 647, 1),
(637, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:45:24', '2022-04-05 21:45:24', 648, 1),
(638, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:51:50', '2022-04-05 21:51:50', 649, 1),
(639, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:58:02', '2022-04-05 21:58:02', 650, 1),
(640, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:02:11', '2022-04-05 22:02:11', 651, 1),
(641, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:06:09', '2022-04-05 22:06:09', 652, 1),
(642, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:09:05', '2022-04-05 22:09:05', 653, 1),
(643, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:15:39', '2022-04-05 22:15:39', 654, 1),
(644, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:19:11', '2022-04-05 22:19:11', 655, 1),
(645, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:23:28', '2022-04-05 22:23:28', 656, 1),
(646, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:26:13', '2022-04-05 22:26:13', 657, 1),
(647, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:29:03', '2022-04-05 22:29:03', 658, 1),
(648, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:34:15', '2022-04-05 22:34:15', 659, 41),
(649, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:55:02', '2022-04-05 22:55:02', 660, 1),
(650, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:57:46', '2022-04-05 22:57:46', 661, 1),
(651, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:00:40', '2022-04-05 23:00:40', 662, 1),
(652, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:05:20', '2022-04-05 23:05:20', 663, 1),
(653, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:08:01', '2022-04-05 23:08:01', 664, 1),
(654, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:13:57', '2022-04-05 23:13:57', 665, 1),
(655, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:17:02', '2022-04-05 23:17:02', 666, 1),
(656, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:19:19', '2022-04-05 23:19:19', 667, 1),
(657, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:21:57', '2022-04-05 23:21:57', 668, 1),
(658, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:24:47', '2022-04-05 23:24:47', 669, 1),
(659, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:28:35', '2022-04-05 23:28:35', 670, 1),
(660, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:31:15', '2022-04-05 23:31:15', 671, 1),
(661, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:34:09', '2022-04-05 23:34:09', 672, 1),
(662, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:36:38', '2022-04-05 23:36:38', 673, 1),
(663, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:39:42', '2022-04-05 23:39:42', 674, 1),
(664, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:42:46', '2022-04-05 23:42:46', 675, 1),
(665, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:46:06', '2022-04-05 23:46:06', 676, 1),
(666, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:49:21', '2022-04-05 23:49:21', 677, 1),
(667, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:51:59', '2022-04-05 23:51:59', 678, 1),
(668, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:54:11', '2022-04-05 23:54:11', 679, 1),
(669, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:56:39', '2022-04-05 23:56:39', 680, 1),
(670, '2022-04-05', '2022-04-05', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:59:03', '2022-04-05 23:59:03', 681, 1),
(671, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 00:05:06', '2022-04-06 00:05:06', 682, 1),
(672, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 00:07:25', '2022-04-06 00:07:25', 683, 1),
(673, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 00:09:34', '2022-04-06 00:09:34', 684, 1),
(674, '2022-04-06', '2022-04-06', NULL, NULL, NULL, '2022-04-06', '2022-04-06', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 09:47:28', '2022-04-06 09:52:41', 685, 1),
(675, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:10:05', '2022-04-06 10:10:05', 686, 40),
(676, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:21:38', '2022-04-06 10:21:38', 687, 1),
(677, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:24:49', '2022-04-06 10:24:49', 688, 1),
(678, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:29:06', '2022-04-06 10:29:06', 689, 40),
(679, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:32:28', '2022-04-06 10:32:28', 690, 1),
(680, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:37:54', '2022-04-06 10:37:54', 691, 1),
(681, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:44:18', '2022-04-06 10:44:18', 692, 1),
(682, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:49:19', '2022-04-06 10:49:19', 693, 1),
(683, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:53:14', '2022-04-06 10:53:14', 694, 1),
(684, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:00:36', '2022-04-06 11:00:36', 695, 1),
(685, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:08:50', '2022-04-06 11:08:50', 696, 1),
(686, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:14:18', '2022-04-06 11:14:18', 697, 1),
(687, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:21:33', '2022-04-06 11:21:33', 698, 1),
(688, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:25:06', '2022-04-06 11:25:06', 699, 1),
(689, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:27:43', '2022-04-06 11:27:43', 700, 1),
(690, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:30:19', '2022-04-06 11:30:19', 701, 1),
(691, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:38:34', '2022-04-06 11:38:34', 702, 44),
(692, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:41:31', '2022-04-06 11:41:31', 703, 1),
(693, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:47:59', '2022-04-06 11:47:59', 704, 1),
(694, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:54:20', '2022-04-06 11:54:20', 705, 1),
(695, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:58:45', '2022-04-06 11:58:45', 706, 1),
(696, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:01:30', '2022-04-06 12:01:30', 707, 1),
(697, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:13:19', '2022-04-06 12:13:19', 708, 1),
(698, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:22:11', '2022-04-06 12:22:11', 709, 1),
(699, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:39:24', '2022-04-06 12:39:24', 710, 1),
(700, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:44:16', '2022-04-06 12:44:16', 711, 1),
(701, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:46:36', '2022-04-06 12:46:36', 712, 1),
(702, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:03:33', '2022-04-06 13:03:33', 713, 1),
(703, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:18:07', '2022-04-06 13:18:07', 714, 1),
(704, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:36:35', '2022-04-06 13:36:35', 715, 1),
(705, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:42:26', '2022-04-06 13:42:26', 716, 1),
(706, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:46:53', '2022-04-06 13:46:53', 717, 1),
(707, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:26:38', '2022-04-06 14:26:38', 718, 1),
(708, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:30:51', '2022-04-06 14:30:51', 719, 1),
(709, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:33:12', '2022-04-06 14:33:12', 720, 1),
(710, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:37:55', '2022-04-06 14:37:55', 721, 1),
(711, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:40:27', '2022-04-06 14:40:27', 722, 1),
(712, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:43:18', '2022-04-06 14:43:18', 723, 1),
(713, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:46:36', '2022-04-06 14:46:36', 724, 1),
(714, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:55:24', '2022-04-06 14:55:24', 725, 1),
(715, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:58:43', '2022-04-06 14:58:43', 726, 1),
(716, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:01:04', '2022-04-06 15:01:04', 727, 1),
(717, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:04:26', '2022-04-06 15:04:26', 728, 1),
(718, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:08:23', '2022-04-06 15:08:23', 729, 1),
(719, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:13:17', '2022-04-06 15:13:17', 730, 1),
(720, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:16:42', '2022-04-06 15:16:42', 731, 1),
(721, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:19:44', '2022-04-06 15:19:44', 732, 1),
(722, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:22:02', '2022-04-06 15:22:02', 733, 1),
(723, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:26:41', '2022-04-06 15:26:41', 734, 1),
(724, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:29:35', '2022-04-06 15:29:35', 735, 1),
(725, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:33:10', '2022-04-06 15:33:10', 736, 1),
(726, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:35:44', '2022-04-06 15:35:44', 737, 1),
(727, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:39:12', '2022-04-06 15:39:12', 738, 1),
(728, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:45:08', '2022-04-06 15:45:08', 739, 1),
(729, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:50:27', '2022-04-06 15:50:27', 740, 1),
(730, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:54:49', '2022-04-06 15:54:49', 742, 1),
(731, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:57:39', '2022-04-06 15:57:39', 743, 1);
INSERT INTO `CONTRATO` (`CTO_CODIGO`, `CTO_FECHA_TRAMITE`, `CTO_FECHA_INICIO`, `CTO_FECHA_ANULACION`, `CTO_FECHA_SUSPENCION`, `CTO_FECHA_RECONECCION`, `CTO_FECHA_INICIO_MANTENIMIENTO`, `CTO_FECHA_FIN_MANTENIMIENTO`, `CTO_OBSERVACION`, `CTO_ESTADO`, `CTO_AGU_CAR_CNX`, `CTO_AGU_DTO_CNX`, `CTO_AGU_DTO_RED`, `CTO_AGU_FEC_INS`, `CTO_AGU_MAT_CNX`, `CTO_AGU_MAT_ABA`, `CTO_AGU_UBI_CAJ`, `CTO_AGU_MAT_CAJ`, `CTO_AGU_EST_CAJ`, `CTO_AGU_MAT_TAP`, `CTO_AGU_EST_TAP`, `CTO_ALC_CAR_CNX`, `CTO_ALC_TIP_CNX`, `CTO_ALC_FEC_INS`, `CTO_ALC_MAT_CNX`, `CTO_ALC_DTO_RED`, `CTO_ALC_DTO_CNX`, `CTO_ALC_UBI_CAJ`, `CTO_ALC_MAT_CAJ`, `CTO_ALC_EST_CAJ`, `CTO_ALC_DIM_CAJ`, `CTO_ALC_MAT_TAP`, `CTO_ALC_EST_TAP`, `CTO_ALC_MED_TAP`, `CTO_CREATED`, `CTO_UPDATED`, `PRE_CODIGO`, `TUP_CODIGO`) VALUES
(732, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:05:53', '2022-04-06 16:05:53', 741, 1),
(733, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:16:25', '2022-04-06 16:16:25', 744, 1),
(734, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:29:33', '2022-04-06 16:29:33', 746, 1),
(735, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:32:39', '2022-04-06 16:32:39', 747, 1),
(736, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:35:54', '2022-04-06 16:35:54', 749, 1),
(737, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:40:34', '2022-04-06 16:40:34', 750, 1),
(738, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:52:32', '2022-04-06 16:52:32', 748, 1),
(739, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:54:53', '2022-04-06 16:54:53', 751, 1),
(740, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:59:11', '2022-04-06 16:59:11', 752, 1),
(741, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:01:37', '2022-04-06 17:01:37', 753, 1),
(742, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:04:24', '2022-04-06 17:04:24', 754, 1),
(743, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:06:35', '2022-04-06 17:06:35', 755, 1),
(744, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:08:53', '2022-04-06 17:08:53', 756, 1),
(745, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:11:11', '2022-04-06 17:11:11', 757, 1),
(746, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:16:02', '2022-04-06 17:16:02', 758, 1),
(747, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:18:52', '2022-04-06 17:18:52', 759, 1),
(748, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:22:32', '2022-04-06 17:22:32', 760, 1),
(749, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:31:05', '2022-04-06 17:31:05', 761, 1),
(750, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:33:27', '2022-04-06 17:33:27', 762, 1),
(751, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:35:23', '2022-04-06 17:35:23', 763, 1),
(752, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:45:18', '2022-04-06 17:45:18', 764, 1),
(753, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:56:23', '2022-04-06 17:56:23', 765, 40),
(754, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 18:00:38', '2022-04-06 18:00:38', 766, 1),
(755, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 18:04:20', '2022-04-06 18:04:20', 767, 1),
(756, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 18:07:42', '2022-04-06 18:07:42', 768, 1),
(757, '2022-04-06', '2022-04-06', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 18:11:23', '2022-04-06 18:11:23', 769, 1),
(758, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:40:43', '2022-04-07 10:40:43', 770, 1),
(759, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:44:32', '2022-04-07 10:44:32', 771, 1),
(760, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:46:35', '2022-04-07 10:46:35', 772, 1),
(761, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:49:03', '2022-04-07 10:49:03', 773, 1),
(762, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:54:52', '2022-04-07 10:54:52', 774, 1),
(763, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:57:11', '2022-04-07 10:57:11', 775, 1),
(764, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:58:51', '2022-04-07 10:58:51', 776, 1),
(765, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 11:01:20', '2022-04-07 11:01:20', 777, 1),
(766, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 11:44:16', '2022-04-07 11:44:16', 778, 1),
(767, '2022-04-07', '2022-04-07', NULL, '2022-04-07', '2022-04-07', '2022-04-25', '2022-04-25', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 11:47:42', '2022-04-25 12:05:59', 124, 1),
(768, '2022-04-07', '2022-04-07', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 14:36:16', '2022-04-07 14:36:16', 145, 1),
(769, '2022-04-22', '2022-04-22', NULL, NULL, NULL, '2022-04-22', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 17:32:29', '2022-04-22 17:32:36', 56, 1),
(770, '2022-04-22', '2022-04-22', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 17:49:15', '2022-04-22 17:49:15', 160, 1),
(771, '2022-04-22', '2022-04-22', NULL, '2022-04-22', NULL, NULL, NULL, '', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 18:05:58', '2022-04-22 18:06:05', 137, 1),
(772, '2022-04-22', '2022-04-22', NULL, NULL, NULL, '2022-04-22', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 18:32:31', '2022-04-22 18:33:28', 437, 1),
(773, '2022-04-22', '2022-04-22', NULL, NULL, NULL, '2022-04-22', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 18:39:22', '2022-04-22 18:39:29', 149, 1),
(774, '2022-04-22', '2022-04-22', NULL, NULL, NULL, '2022-04-22', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 18:59:23', '2022-04-22 18:59:28', 176, 1),
(775, '2022-04-22', '2022-04-22', NULL, NULL, NULL, '2022-04-22', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 19:04:15', '2022-04-22 19:04:20', 146, 1),
(776, '2022-04-22', '2022-04-22', NULL, NULL, NULL, '2022-04-22', NULL, '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 19:07:11', '2022-04-22 19:07:17', 127, 1),
(777, '2022-04-22', '2022-04-22', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-22 19:10:43', '2022-04-22 19:10:43', 745, 1),
(778, '2022-04-25', '2022-04-25', NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-25 12:34:57', '2022-04-25 12:34:57', 455, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CUOTA_EXTRAORDINARIA`
--

CREATE TABLE `CUOTA_EXTRAORDINARIA` (
  `CUE_CODIGO` int NOT NULL,
  `CUE_NUM_CUOTA` int NOT NULL,
  `CUE_MNTO_CUOTA` double(5,2) NOT NULL,
  `CUE_ESTADO` int NOT NULL,
  `CUE_CREATED` datetime NOT NULL,
  `CUE_UPDATED` datetime NOT NULL,
  `PTO_CODIGO` int NOT NULL,
  `CTO_CODIGO` int NOT NULL,
  `IGR_CODIGO` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EGRESO`
--

CREATE TABLE `EGRESO` (
  `EGR_CODIGO` int NOT NULL,
  `EGR_CANTIDAD` double(5,2) NOT NULL,
  `EGR_TIPO_COMPROBANTE` int NOT NULL,
  `EGR_COD_COMPROBANTE` varchar(16) COLLATE utf8_spanish_ci DEFAULT '',
  `EGR_TIPO` varchar(7) COLLATE utf8_spanish_ci NOT NULL,
  `EGR_DESCRIPCION` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `EGR_ESTADO` int NOT NULL,
  `EGR_CREATED` datetime NOT NULL,
  `EGR_UPDATED` datetime NOT NULL,
  `USU_CODIGO` int NOT NULL,
  `CAJ_CODIGO` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `FINANCIAMIENTO`
--

CREATE TABLE `FINANCIAMIENTO` (
  `FTO_CODIGO` int NOT NULL,
  `FTO_DEUDA` double(5,2) NOT NULL,
  `FTO_CUOTA_INICIAL` double(5,2) NOT NULL,
  `FTO_MONTO_CUOTA` double(5,2) NOT NULL,
  `FTO_NUM_CUOTAS` int NOT NULL,
  `FTO_OBSERVACION` varchar(256) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `FTO_ESTADO` int NOT NULL,
  `CTO_CODIGO` int DEFAULT NULL,
  `FTO_CREATED` datetime NOT NULL,
  `FTO_UPDATED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `FINANC_CUOTA`
--

CREATE TABLE `FINANC_CUOTA` (
  `FCU_CODIGO` int NOT NULL,
  `FCU_NUM_CUOTA` int NOT NULL,
  `FCU_MONTO_CUOTA` double(5,2) NOT NULL,
  `FCU_ESTADO` int NOT NULL,
  `FCU_FECHA_DE_CRONOGRAMA` date NOT NULL,
  `FCU_CREATED` datetime NOT NULL,
  `FCU_UPDATED` datetime NOT NULL,
  `FTO_CODIGO` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IGV`
--

CREATE TABLE `IGV` (
  `IGV_CODIGO` int NOT NULL,
  `IGV_PORCENTAJE` int NOT NULL,
  `IGV_CREATED` datetime NOT NULL,
  `IGV_UPDATED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `IGV`
--

INSERT INTO `IGV` (`IGV_CODIGO`, `IGV_PORCENTAJE`, `IGV_CREATED`, `IGV_UPDATED`) VALUES
(1, 0, '2022-02-10 00:10:48', '2022-07-02 15:56:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `INGRESO`
--

CREATE TABLE `INGRESO` (
  `IGR_CODIGO` int NOT NULL,
  `IGR_CANTIDAD` double(8,2) NOT NULL,
  `IGR_IGV` double(5,2) NOT NULL,
  `IGR_MNTO_RECIBIDO` double(8,2) DEFAULT NULL,
  `IGR_TIPO_COMPROBANTE` int NOT NULL,
  `IGR_COD_COMPROBANTE` varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL,
  `IGR_DESCRIPCION` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `IGR_TIPO` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `IGR_ESTADO` int NOT NULL,
  `IGR_CREATED` datetime NOT NULL,
  `IGR_UPDATED` datetime NOT NULL,
  `USU_CODIGO` int NOT NULL,
  `CAJ_CODIGO` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PREDIO`
--

CREATE TABLE `PREDIO` (
  `PRE_CODIGO` int NOT NULL,
  `PRE_DIRECCION` varchar(260) COLLATE utf8_spanish_ci NOT NULL,
  `PRE_HABITADA` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `PRE_MAT_CONSTRUCCION` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `PRE_PISOS` int DEFAULT NULL,
  `PRE_FAMILIAS` int DEFAULT NULL,
  `PRE_HABITANTES` int DEFAULT NULL,
  `PRE_POZO_TABULAR` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `PRE_PISCINA` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `PRE_CREATED` datetime NOT NULL,
  `PRE_UPDATED` datetime NOT NULL,
  `CLI_CODIGO` int NOT NULL,
  `CAL_CODIGO` int NOT NULL,
  `PRE_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `PREDIO`
--

INSERT INTO `PREDIO` (`PRE_CODIGO`, `PRE_DIRECCION`, `PRE_HABITADA`, `PRE_MAT_CONSTRUCCION`, `PRE_PISOS`, `PRE_FAMILIAS`, `PRE_HABITANTES`, `PRE_POZO_TABULAR`, `PRE_PISCINA`, `PRE_CREATED`, `PRE_UPDATED`, `CLI_CODIGO`, `CAL_CODIGO`, `PRE_DELETED`) VALUES
(1, 'AV. Tupac Amaru S/N - LT. A', 'si', 'adobe', 1, 2, 6, 'no', 'no', '2022-02-10 15:07:52', '2022-03-28 21:04:11', 11, 1, NULL),
(2, 'AV. TUPAC AMARU N° 413 - MZ 10 - LT 7  B', 'si', 'noble', 1, 1, 7, 'no', 'no', '2022-02-10 16:44:56', '2022-03-28 20:09:46', 46, 1, NULL),
(3, 'AV. CESAR VALLEJO N° 311', 'si', 'noble', 2, 2, 6, 'no', 'no', '2022-02-10 17:30:44', '2022-03-30 10:16:48', 66, 2, NULL),
(4, 'AV. CESAR VALLEJO # 207 - LT 1', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-10 17:59:11', '2022-03-30 10:16:05', 75, 2, NULL),
(5, 'JOSE QUIÑONES S/N', '', '', 0, 0, 0, '', '', '2022-02-10 18:07:52', '2022-04-07 13:01:32', 78, 10, NULL),
(6, 'AV. TUPAC AMARU 451 MZ 10 - LT 7 A', 'no', 'adobe', 1, 0, 0, 'no', 'no', '2022-02-21 12:50:27', '2022-03-28 20:11:53', 107, 1, NULL),
(7, 'AV. TUPAC AMARU N° 355 - MZ. 8 LT.01', 'si', 'noble', 2, 2, 6, 'no', 'no', '2022-02-22 14:44:14', '2022-02-22 14:44:14', 81, 1, NULL),
(8, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 10, 'si', 'si', '2022-02-22 14:45:23', '2022-02-22 14:45:23', 1, 1, NULL),
(9, 'Av. Tupac Amaru 467 - MZ 10 - LT 15', 'no', 'noble', 2, 0, 0, 'no', 'no', '2022-02-23 15:53:34', '2022-03-28 21:18:11', 177, 1, NULL),
(10, 'Av. Tupac Amaru S/N - MZ 16 - LT 3-B', 'si', 'adobe', 0, 0, 0, 'no', 'no', '2022-02-23 15:54:40', '2022-03-28 21:21:48', 179, 1, NULL),
(11, 'AV. TUPAC AMARU S/N', 'si', 'adobe', 2, 2, 10, 'no', 'no', '2022-02-23 15:55:24', '2022-02-23 15:55:24', 2, 1, NULL),
(12, 'AV. TUPAC AMARU N° 511', 'si', 'adobe', 1, 2, 6, 'no', 'no', '2022-02-23 15:56:22', '2022-02-23 15:56:22', 3, 1, NULL),
(13, 'AV. TUPAC AMARU N° 439 - MZ. 10 LT.24', 'si', 'adobe', 1, 2, 6, 'no', 'no', '2022-02-23 15:57:46', '2022-02-23 15:57:46', 4, 1, NULL),
(14, 'AV. TUPAC AMARU 459 -  MZ 10 LT 11', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 16:00:00', '2022-02-23 16:00:00', 111, 1, NULL),
(15, 'AV. TUPAC AMARU N° 421 - MZ. 8 LT.15', 'si', 'noble', 1, 1, 3, 'no', 'no', '2022-02-23 16:01:53', '2022-02-23 16:01:53', 82, 1, NULL),
(16, 'AV. TUPAC AMRU S/N', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:02:52', '2022-02-23 16:02:52', 140, 1, NULL),
(17, 'AV. TUPAC AMARU N° 325 -  MZ 8 LT.14 10', 'si', 'adobe', 1, 1, 4, 'no', 'no', '2022-02-23 16:04:10', '2022-02-23 16:04:10', 5, 1, NULL),
(18, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:04:54', '2022-02-23 16:04:54', 6, 1, NULL),
(19, 'AV. TUPAC AMARU N° 247', 'si', 'noble', 1, 1, 3, 'no', 'no', '2022-02-23 16:05:49', '2022-02-23 16:05:49', 83, 1, NULL),
(20, 'AV. TUPAC AMARU N° 463 -MZ.10 LT.13', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:06:55', '2022-02-23 16:06:55', 7, 1, NULL),
(21, 'AV. TUPAC AMARU S/N - MZ.9 LT.4B', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:08:19', '2022-02-23 16:08:19', 8, 1, NULL),
(22, 'AV. TUPAC AMARU S/N - MZ.8 LT.18', 'no', 'adobe', 1, 0, 0, 'no', 'no', '2022-02-23 16:09:20', '2022-02-23 16:09:20', 9, 1, NULL),
(23, 'AV. TUPAC AMARU N° 515', 'si', 'noble', 1, 1, 3, 'si', 'no', '2022-02-23 16:10:14', '2022-02-23 16:10:14', 138, 1, NULL),
(24, 'AV. TUPAC AMARU N° 419 - MZ.8 LT.14', 'si', 'noble', 1, 1, 3, 'no', 'no', '2022-02-23 16:11:22', '2022-02-23 16:11:22', 10, 1, NULL),
(25, 'AV. TUPAC AMARU N° 423 . MZ.8 LT.158', 'si', 'adobe', 1, 1, 3, 'no', 'no', '2022-02-23 16:14:29', '2022-02-23 16:14:29', 84, 1, NULL),
(26, 'Av. Tupac Amaru S/N - LT. B', 'si', 'noble', 2, 1, 7, 'no', 'no', '2022-02-23 16:15:16', '2022-03-28 21:06:02', 11, 1, NULL),
(27, 'AV. TUPAC AMARU N° 507-MZ.14 LT.4', 'si', 'noble', 2, 2, 6, 'no', 'no', '2022-02-23 16:16:47', '2022-02-23 16:16:47', 12, 1, NULL),
(28, 'AV. TUPAC AMARU N° 345-MZ.8 LT.7', 'si', 'adobe', 1, 1, 3, 'no', 'no', '2022-02-23 16:17:41', '2022-02-23 16:17:41', 85, 1, NULL),
(29, 'AV. TUPAC AMARU N° 533-MZ.16 LT.3D', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:19:26', '2022-02-23 16:19:26', 13, 1, NULL),
(30, 'AV. TUPAC AMARU S/N -MZ.16 LT.F', 'si', 'adobe', 2, 1, 4, 'si', 'si', '2022-02-23 16:20:32', '2022-02-23 16:20:32', 139, 1, NULL),
(31, 'AV. TUPAC AMARU S/N - MZ.4 LT.4A', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:21:28', '2022-02-23 16:21:28', 115, 1, NULL),
(32, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:22:08', '2022-02-23 16:22:08', 14, 1, NULL),
(33, 'AV. TUPAC AMARU S/N -MZ.10 LT.9', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:23:03', '2022-02-23 16:23:03', 116, 1, NULL),
(34, 'AV. TUPAC AMARU N° 509-MZ.14 LT.4A', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:24:25', '2022-02-23 16:24:25', 16, 1, NULL),
(35, 'AV. TUPAC AMARU N° 217  - LT 1', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:25:45', '2022-04-06 17:42:30', 17, 1, NULL),
(36, 'AV. TUPAC AMARU N° 461-MZ.10 LT.14', NULL, 'noble', 1, 1, NULL, 'no', 'no', '2022-02-23 16:27:02', '2022-02-23 16:27:02', 18, 1, NULL),
(37, 'AV. TUPAC AMARU N° 299', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:27:51', '2022-02-23 16:27:51', 86, 1, NULL),
(38, 'AV. TUPAC AMARU N° 447 - MZ.10 LT.5.', 'si', 'noble', 2, 1, 6, 'si', 'no', '2022-02-23 16:29:46', '2022-02-23 16:29:46', 87, 1, NULL),
(39, 'AV. TUPAC AMARU N° 263', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:30:33', '2022-02-23 16:30:33', 114, 1, NULL),
(40, 'AV. TUPAC AMARU S/N', 'no', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:31:21', '2022-02-23 16:31:21', 88, 1, NULL),
(41, 'AV. TUPAC AMARU N° 257', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 16:32:33', '2022-02-23 16:32:33', 89, 1, NULL),
(42, 'AV. TUPAC AMARU N° 224', 'si', 'noble', 2, 1, 4, 'no', 'no', '2022-02-23 16:33:28', '2022-02-23 16:33:28', 90, 1, NULL),
(43, 'AV. TUPAC AMARU S/N - MZ.12 LT.01', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:34:23', '2022-02-23 16:34:23', 19, 1, NULL),
(44, 'Av. Tupac Amaru S/N - LT. A', 'no', 'adobe', 1, 0, 0, 'no', 'no', '2022-02-23 16:38:16', '2022-03-28 21:58:03', 193, 1, NULL),
(45, 'Av. Tupac Amaru S/N - LT.B', 'no', 'adobe', 0, 0, 0, 'no', 'no', '2022-02-23 16:38:16', '2022-03-28 22:00:43', 193, 1, NULL),
(46, 'AV. TUPAC AMARU N° 417 -MZ.8 LT.12A', 'si', 'noble', 2, 2, 6, 'no', 'no', '2022-02-23 16:39:29', '2022-02-23 16:39:29', 91, 1, NULL),
(47, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:40:16', '2022-02-23 16:40:16', 20, 1, NULL),
(48, 'AV. TUPAC AMARU N° 481 - MZ.12 LT.8', 'si', 'adobe', 1, 1, 4, 'no', 'no', '2022-02-23 16:41:24', '2022-02-23 16:41:24', 141, 1, NULL),
(49, 'AV. TUPAC AMARU N° 317 - MZ.6 LT.10', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:42:23', '2022-02-23 16:42:23', 92, 1, NULL),
(50, 'AV. TUPAC AMARU S/N - MZ.8 LT.10', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:43:13', '2022-02-23 16:43:13', 93, 1, NULL),
(51, 'AV. TUPAC AMARU N° 413-MZ.8 LT.11', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 16:44:08', '2022-02-23 16:44:08', 21, 1, NULL),
(52, 'AV. TUPAC AMARU N° 515 -MZ.14 LT.14E', 'si', 'noble', 2, 2, 6, 'no', 'no', '2022-02-23 16:45:07', '2022-02-23 16:45:07', 22, 1, NULL),
(53, 'AV. TUPAC AMARU S/N - MZ.18 LT.4', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:45:57', '2022-02-23 16:45:57', 23, 1, NULL),
(54, 'AV. TUPAC AMARU N° 347 - MZ.6 LT.25', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 16:48:24', '2022-02-23 16:48:24', 144, 1, NULL),
(55, 'Av. Tupac Amaru  291- MZ 4 - LT B-1', 'no', 'adobe', 0, 0, 0, 'no', 'no', '2022-02-23 16:49:22', '2022-03-29 00:23:12', 181, 1, NULL),
(56, 'AV. TUPAC AMARU S/N - MZ.6 LT.15', 'no', 'noble', 1, 0, 0, 'no', 'no', '2022-02-23 16:49:22', '2022-02-23 16:49:22', 24, 1, NULL),
(57, 'AV. TUPAC AMARU N° 225 - MZ.C LT.2', 'si', NULL, 1, 1, 6, 'no', 'no', '2022-02-23 16:50:20', '2022-02-23 16:50:20', 25, 1, NULL),
(58, 'CAJAMARCA S/N', '', '', 0, 0, 0, '', '', '2022-02-23 16:51:23', '2022-04-25 18:41:16', 178, 6, NULL),
(59, 'AV. TUPAC AMARU S/N - MZ.10 LT.8', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 16:52:18', '2022-02-23 16:52:18', 94, 1, NULL),
(60, 'AV. TUPAC AMARU N° 299', 'si', 'noble', 3, 2, 6, 'no', 'si', '2022-02-23 16:54:59', '2022-02-23 16:54:59', 145, 1, NULL),
(61, 'AV. TUPAC AMARU S/N', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 16:57:02', '2022-02-23 16:57:02', 95, 1, NULL),
(62, 'AV. TUPAC AMARU N° 329 - MZ.6 LT.16', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 16:58:04', '2022-02-23 16:58:04', 26, 1, NULL),
(63, 'AV. TUPAC AMARU N° 313- MZ.6 LT.8', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:00:21', '2022-02-23 17:00:21', 96, 1, NULL),
(64, 'AV. TUPAC AMARU S/N - MZ.8 LT.20', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 17:01:34', '2022-02-23 17:01:34', 97, 1, NULL),
(65, 'AV. TUPAC AMARU N° 435 - MZ.8 LT.22', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 17:02:26', '2022-02-23 17:02:26', 27, 1, NULL),
(66, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 17:04:57', '2022-02-23 17:04:57', 146, 1, NULL),
(67, 'AV. TUPAC AMARU N° 469 - MZ.10 LT.16', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 17:05:58', '2022-02-23 17:05:58', 122, 1, NULL),
(68, 'AV. TUPAC AMARU S/N', '', '', 0, 0, 0, '', '', '2022-02-23 17:08:08', '2022-02-23 17:08:43', 112, 1, NULL),
(69, 'AV. TUPAC AMARU S/N - mz.12 lt.09', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:30:57', '2022-02-23 17:30:57', 28, 1, NULL),
(70, 'AV. TUPAC AMARU S/N - MZ.8 LT.20A', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 17:31:49', '2022-02-23 17:31:49', 123, 1, NULL),
(71, 'AV. TUPAC AMARU N° 471 - MZ.10 LT.17', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:32:47', '2022-02-23 17:32:47', 29, 1, NULL),
(72, 'AV. TUPAC AMARU N° 351 - MZ.6 LT.26', 'si', 'noble', 1, 1, 10, 'no', 'no', '2022-02-23 17:33:53', '2022-02-23 17:33:53', 98, 1, NULL),
(73, 'AV. TUPAC AMARU N° 353 -MZ.6 LT.28', 'si', 'adobe', 1, 2, 6, 'no', 'no', '2022-02-23 17:36:25', '2022-02-23 17:36:25', 147, 1, NULL),
(74, 'Av. Tupac Amaru 479 - MZ 12 - LT 3', 'si', 'adobe', 1, 1, 5, 'si', 'no', '2022-02-23 17:37:35', '2022-03-28 20:31:14', 110, 1, NULL),
(75, 'AV. TUPAC AMARU N° 517 - MZ.14 LT.4F', NULL, 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:38:33', '2022-02-23 17:38:33', 113, 1, NULL),
(76, 'AV. TUPAC AMARU N° 409 - MZ.8 LT.8', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:39:30', '2022-02-23 17:39:30', 125, 1, NULL),
(77, 'AV. TUPAC AMARU N° 341 - MZ.6 LT.22', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:40:35', '2022-02-23 17:40:35', 124, 1, NULL),
(78, 'AV. TUPAC AMARU N° 491 -MZ.12 LT.9A', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:41:35', '2022-02-23 17:41:35', 31, 1, NULL),
(79, 'AV. TUPAC AMARU N° 309 - MZ.6 LT.6', 'si', 'adobe', 1, 2, 6, 'no', 'no', '2022-02-23 17:42:35', '2022-02-23 17:42:35', 99, 1, NULL),
(80, 'AV. TUPAC AMARU N° 473 - MZ.10 LT.18', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 17:43:32', '2022-02-23 17:43:32', 121, 1, NULL),
(81, 'AV. TUPAC AMARU S/N - MZ.8 LT.15A', 'no', 'noble', 2, 0, 0, 'no', 'no', '2022-02-23 17:44:31', '2022-02-23 17:44:31', 32, 1, NULL),
(82, 'AV. TUPAC AMARU S/N - MZ.14 LT.1A', 'si', 'noble', 1, 1, 6, 'si', 'no', '2022-02-23 17:45:18', '2022-02-23 17:45:18', 33, 1, NULL),
(83, 'AV. TUPAC AMARU N° 307 - MZ.6 LT.5/6', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:46:15', '2022-02-23 17:46:15', 80, 1, NULL),
(84, 'AV. TUPAC AMARU N° 343 - MZ.8 LT.6', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:47:16', '2022-02-23 17:47:16', 100, 1, NULL),
(85, 'AV. TUPAC AMARU N° 478', 'si', 'noble', 2, 1, 6, 'no', 'no', '2022-02-23 17:48:02', '2022-02-23 17:48:02', 34, 1, NULL),
(86, 'AV. TUPAC AMARU N° 301 - MZ.6 LT.1', 'si', 'noble', 2, 1, 6, 'si', 'no', '2022-02-23 17:49:01', '2022-02-23 17:49:01', 35, 1, NULL),
(87, 'AV. TUPAC AMARU S/N - MZ.6 LT.3', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:49:50', '2022-02-23 17:49:50', 120, 1, NULL),
(88, 'AV. TUPAC AMARU N° 345 - MZ.8 LT.24', 'si', 'adobe', 1, 1, 6, 'no', 'no', '2022-02-23 17:50:56', '2022-02-23 17:50:56', 101, 1, NULL),
(89, 'AV. TUPAC AMARU S/N - MZ.12 LT.7', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:51:41', '2022-02-23 17:51:41', 36, 1, NULL),
(90, 'AV. TUPAC AMARU S/N - MZ.18 LT.3C1', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:52:31', '2022-02-23 17:52:31', 37, 1, NULL),
(91, 'AV. TUPAC AMARU N° 321 - MZ.6 LT.12', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:53:32', '2022-02-23 17:53:32', 102, 1, NULL),
(92, 'AV. TUPAC AMARU N° 213 - MZ.2 LT.1', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:54:37', '2022-02-23 17:54:37', 38, 1, NULL),
(93, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:55:16', '2022-02-23 17:55:16', 39, 1, NULL),
(94, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:55:52', '2022-02-23 17:55:52', 40, 1, NULL),
(95, 'AV. TUPAC AMARU N° 440 - MZ.10 LT.1', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:56:51', '2022-02-23 17:56:51', 41, 1, NULL),
(96, 'AV. TUPAC AMARU N° 441 - MZ.10 LT.2', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:57:39', '2022-02-23 17:57:39', 119, 1, NULL),
(97, 'AV. TUPAC AMARU N° 449 - MZ.10 LT.6', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 17:58:35', '2022-02-23 17:58:35', 118, 1, NULL),
(98, 'AV. TUPAC AMARU N° 425 - MZ.8 LT.16A', 'si', 'noble', 2, 1, 6, 'no', 'no', '2022-02-23 17:59:40', '2022-02-23 17:59:40', 42, 1, NULL),
(99, 'AV. TUPAC AMARU N° 445 - MZ.10 LT.4', 'si', 'noble', 2, 1, 6, 'no', 'no', '2022-02-23 18:00:27', '2022-02-23 18:00:27', 103, 1, NULL),
(100, 'AV. TUPAC AMARU N° 333 . MZ.6 LT.19', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 18:16:06', '2022-02-23 18:16:06', 104, 1, NULL),
(101, 'AV. TUPAC AMARU N° 415 - MZ.8 LT.12', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 18:17:05', '2022-02-23 18:17:05', 43, 1, NULL),
(102, 'AV. TUPAC AMARU N° 203', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 18:18:03', '2022-02-23 18:18:03', 105, 1, NULL),
(103, 'AV. TUPAC AMARU N° 241', 'si', 'noble', 2, 2, 6, 'no', 'no', '2022-02-23 18:19:02', '2022-02-23 18:19:02', 106, 1, NULL),
(104, 'AV. TUPAC AMARU S/N - MZ.8 LT.13', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 18:20:00', '2022-02-23 18:20:00', 136, 1, NULL),
(105, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-02-23 18:20:43', '2022-02-23 18:20:43', 44, 1, NULL),
(106, 'Av. Tupac Amaru  413 - MZ 10 - LT7D', 'si', 'noble', 2, 0, 0, 'no', 'no', '2022-02-23 18:22:24', '2022-03-28 19:55:48', 107, 1, NULL),
(107, 'Av. Tupac Amaru  413 - MZ 10 - LT 7 C', 'no', 'madera', 0, 0, 0, 'no', 'no', '2022-02-23 18:23:53', '2022-03-28 20:02:29', 46, 1, NULL),
(108, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 1, 1, 'no', 'no', '2022-02-23 18:26:41', '2022-03-28 19:28:58', 254, 1, NULL),
(109, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 2, 4, 'si', 'no', '2022-02-23 18:27:42', '2022-03-28 19:34:19', 255, 1, NULL),
(110, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-02-23 18:27:43', '2022-03-28 19:38:00', 256, 1, NULL),
(111, 'Av. Tupac Amaru  S/N', '', '', 0, 0, 0, '', '', '2022-02-23 18:30:43', '2022-04-25 12:30:50', 148, 1, NULL),
(112, 'AV. TUPAC AMARU N° 429 .- MZ.8 LT.17', 'si', 'noble', 2, 2, 6, 'no', 'no', '2022-02-23 18:31:57', '2022-02-23 18:31:57', 45, 1, NULL),
(113, 'AV. TUPAC AMARU N° 303 - MZ. 6 LT.2', 'si', 'noble', 2, 1, 6, 'no', 'no', '2022-02-23 18:32:59', '2022-02-23 18:32:59', 108, 1, NULL),
(114, 'AV. TUPAC AMARU N° 315 - MZ.6 LT.9', 'si', 'noble', 2, 1, 2, 'no', 'no', '2022-02-23 18:33:57', '2022-02-23 18:33:57', 109, 1, NULL),
(115, 'AV. TUPAC AMARU N° 311 -MZ.6 LT.7', 'si', 'noble', 2, 1, 4, 'no', 'no', '2022-02-23 18:34:52', '2022-04-11 21:28:14', 47, 1, NULL),
(116, 'AV. TUPAC AMARU S/N - MZ.7 LT.4', 'no', 'noble', 1, 0, 0, 'no', 'no', '2022-02-23 18:36:00', '2022-04-11 21:14:31', 137, 1, NULL),
(117, 'AV. TUPAC AMARU S/N', 'si', 'noble', 1, 1, 2, 'no', 'no', '2022-02-23 18:36:46', '2022-04-11 21:33:03', 117, 1, NULL),
(118, 'AV. CESAR VALLEJO #156', 'si', 'adobe', 0, 0, 0, '', '', '2022-03-23 21:49:48', '2022-04-11 21:57:59', 262, 2, NULL),
(119, 'Av. Tupac Amaru S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 23:02:51', '2022-03-29 23:02:51', 182, 1, NULL),
(120, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 1, 2, 'si', 'no', '2022-03-29 23:05:44', '2022-03-29 23:05:44', 183, 1, NULL),
(121, 'Av. Tupac Amaru S/N', 'si', 'noble', 2, 1, 3, 'si', 'no', '2022-03-29 23:09:49', '2022-03-29 23:09:49', 184, 1, NULL),
(122, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 2, 8, 'no', 'no', '2022-03-29 23:12:28', '2022-03-29 23:12:28', 185, 1, NULL),
(123, 'Av. Tupac Amaru 437- MZ 8 - LT 23', 'no', 'noble', 0, 0, 0, 'no', 'no', '2022-03-29 23:15:55', '2022-03-29 23:15:55', 186, 1, NULL),
(124, 'A.v. CESAR VALLEJO 274', '', '', 0, 0, 0, '', '', '2022-03-29 23:22:39', '2022-04-07 13:50:18', 813, 2, NULL),
(125, 'Av. Tupac Amaru S/N', 'si', 'noble', 2, 2, 4, 'si', 'no', '2022-03-29 23:28:21', '2022-03-29 23:28:21', 191, 1, NULL),
(126, 'Av. Tupac Amaru S/N', 'si', 'noble', 2, 1, 5, 'no', 'no', '2022-03-29 23:31:22', '2022-03-29 23:31:22', 192, 1, NULL),
(127, 'PUENTE GRANDE S/N', '', '', 0, 0, 0, '', '', '2022-03-29 23:36:23', '2022-04-20 23:49:14', 195, 12, NULL),
(128, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 1, 2, 'no', 'no', '2022-03-29 23:38:36', '2022-03-29 23:38:36', 196, 1, NULL),
(129, 'SANTA ANITA S/N', '', '', 0, 0, 0, '', '', '2022-03-29 23:42:34', '2022-04-20 23:23:01', 696, 17, NULL),
(130, 'Av. Tupac Amaru  S/N - MZ 14 - LT 1', 'si', 'adobe', 1, 1, 3, 'no', 'no', '2022-03-29 23:47:14', '2022-03-29 23:47:14', 198, 1, NULL),
(131, 'Av. Tupac Amaru  S/N - MZ 4 - LT 4', 'si', 'adobe', 1, 1, 5, 'no', 'no', '2022-03-29 23:52:30', '2022-03-29 23:52:30', 199, 1, NULL),
(132, 'Av. Tupac Amaru  S/N - MZ 4 - LT 3', 'si', 'adobe', 1, 1, 3, 'no', 'no', '2022-03-29 23:57:47', '2022-03-29 23:57:47', 200, 1, NULL),
(133, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 1, 1, 'no', 'no', '2022-03-30 00:06:55', '2022-03-30 00:06:55', 15, 1, NULL),
(134, 'Av. Tupac Amaru  337 - MZ 6 - LT 20-A', 'si', 'noble', 1, 3, 8, 'no', 'no', '2022-03-30 00:22:45', '2022-03-30 00:22:45', 204, 1, NULL),
(135, 'Av. Tupac Amaru  331 - MZ 6 - LT 17', 'si', 'noble', 2, 2, 11, 'no', 'no', '2022-03-30 00:27:11', '2022-03-30 00:27:11', 202, 1, NULL),
(136, 'Av. Tupac Amaru  S/N - MZ 4 - LT B-2', 'si', 'noble', 2, 1, 2, 'no', 'no', '2022-03-30 00:30:50', '2022-03-30 00:30:50', 205, 1, NULL),
(137, 'Av. Tupac Amaru   335 - MZ 6 - LT 19', 'si', 'noble', 1, 1, 3, 'si', 'no', '2022-03-30 00:35:46', '2022-03-30 00:35:46', 206, 1, NULL),
(138, 'Av. Tupac Amaru  323 - MZ 6 - LT 13', 'si', 'noble', 2, 4, 13, 'no', 'no', '2022-03-30 00:39:10', '2022-03-30 00:39:10', 207, 1, NULL),
(139, 'Av. Tupac Amaru  343 - MZ 6 - LT 23', 'si', 'adobe', 1, 1, 4, 'no', 'no', '2022-03-30 00:42:51', '2022-03-30 00:42:51', 208, 1, NULL),
(140, 'Av. Tupac Amaru  349 - MZ 6 - LT 26', 'no', 'adobe', 1, 0, 0, 'no', 'no', '2022-03-30 00:48:54', '2022-03-30 00:48:54', 143, 1, NULL),
(141, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 1, 5, 'no', 'no', '2022-03-30 00:52:28', '2022-03-30 00:52:28', 210, 1, NULL),
(142, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 1, 1, 'no', 'no', '2022-03-30 00:54:56', '2022-03-30 00:54:56', 211, 1, NULL),
(143, 'Av. Tupac Amaru  485 - MZ 12 - LT 6', 'no', 'adobe', 0, 0, 0, 'no', 'no', '2022-03-30 00:57:42', '2022-03-30 00:57:42', 213, 1, NULL),
(144, 'Av. Tupac Amaru  S/N - MZ 8 - LT 4', 'si', 'noble', 2, 1, 4, 'no', 'no', '2022-03-30 01:04:03', '2022-03-30 01:04:03', 214, 1, NULL),
(145, 'A.v. CESAR VALLEJO 255', '', '', 0, 0, 0, '', '', '2022-03-30 01:08:19', '2022-04-07 14:12:10', 813, 2, NULL),
(146, 'ALGARROBOS S/N', '', '', 0, 0, 0, '', '', '2022-03-30 01:10:55', '2022-04-21 00:20:02', 216, 21, NULL),
(147, 'Av. Tupac Amaru  341 - MZ 4 -  LT 8', 'si', 'adobe', 1, 1, 5, 'no', 'no', '2022-03-30 01:14:25', '2022-03-30 01:14:25', 217, 1, NULL),
(148, 'Av. Tupac Amaru  S/N - MZ 8 - LT 21-A', 'si', 'noble', 2, 1, 3, 'no', 'no', '2022-03-30 01:20:59', '2022-03-30 01:20:59', 218, 1, NULL),
(149, 'LETICIA S/N', '', '', 0, 0, 0, '', '', '2022-03-30 01:25:00', '2022-04-21 00:00:57', 220, 15, NULL),
(150, 'Av. Tupac Amaru 489 - MZ 12 - LT 8', 'si', 'adobe', 1, 2, 5, 'no', 'no', '2022-03-30 01:27:44', '2022-03-30 01:27:44', 221, 1, NULL),
(151, 'Av. Tupac Amaru  S/N - MZ 10 - LT 17-A', 'si', 'noble', 1, 3, 10, 'no', 'no', '2022-03-30 01:31:31', '2022-03-30 01:31:31', 222, 1, NULL),
(152, 'Av. Tupac Amaru  S/N - MZ 12 - LT 2', 'si', 'adobe', 1, 2, 5, 'no', 'no', '2022-03-30 01:38:07', '2022-03-30 01:38:07', 223, 1, NULL),
(153, 'Av. Tupac Amaru 339 - MZ 6 - LT 21', 'si', 'adobe', 1, 1, 4, 'no', 'no', '2022-03-30 01:41:31', '2022-03-30 01:41:31', 224, 1, NULL),
(154, 'Av. Tupac Amaru  357 - MZ 8 - LT 2', 'si', 'adobe', 1, 2, 6, 'no', 'no', '2022-03-30 01:44:48', '2022-03-30 01:44:48', 225, 1, NULL),
(155, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 1, 5, 'no', 'no', '2022-03-30 01:48:12', '2022-03-30 01:48:12', 226, 1, NULL),
(156, 'Av. Tupac Amaru  353 - MZ 6 - LT 28', 'si', 'adobe', 1, 1, 2, 'no', 'no', '2022-03-30 01:57:14', '2022-03-30 01:57:14', 229, 1, NULL),
(157, 'Av. Tupac Amaru  S/N - MZ .C - LT	2', 'si', 'adobe', 1, 2, 7, 'si', 'no', '2022-03-30 02:00:26', '2022-03-30 02:00:26', 230, 1, NULL),
(158, 'Av. Tupac Amaru S/N - MZ 8 - LT 16', 'si', 'noble', 2, 2, 5, 'si', 'no', '2022-03-30 02:05:07', '2022-03-30 02:05:07', 231, 1, NULL),
(159, 'Av. Tupac Amaru  465 - MZ 10 - LT 12', 'si', 'adobe', 1, 1, 1, 'no', 'no', '2022-03-30 02:10:15', '2022-03-30 02:10:15', 232, 1, NULL),
(160, 'Av. Tupac Amaru S/N', '', '', 0, 0, 0, '', '', '2022-03-30 02:15:39', '2022-04-22 17:47:37', 233, 1, NULL),
(161, 'Av. Tupac Amaru  S/N - MZ 1 - LT 3', 'si', 'adobe', 1, 1, 5, 'no', 'no', '2022-03-30 02:18:17', '2022-03-30 02:18:17', 234, 1, NULL),
(162, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 2, 5, 'no', 'no', '2022-03-30 02:23:03', '2022-03-30 02:23:03', 235, 1, NULL),
(163, 'Av. Tupac Amaru  523 - MZ 12 - LT 5', 'no', 'noble', 1, 0, 0, 'no', 'no', '2022-03-30 02:26:33', '2022-03-30 02:26:33', 236, 1, NULL),
(164, 'Av. Tupac Amaru  359 - MZ 8 - LT 3', 'si', 'noble', 1, 0, 0, 'no', 'no', '2022-03-30 02:30:14', '2022-03-30 02:30:14', 237, 1, NULL),
(165, 'Av. Tupac Amaru  S/N - MZ C - LT 6', 'si', 'adobe', 1, 1, 2, 'no', 'no', '2022-03-30 02:33:16', '2022-03-30 02:33:16', 126, 1, NULL),
(166, 'Av. Tupac Amaru  411 - MZ 8 - LT 9', 'si', 'noble', 2, 3, 2, 'no', 'no', '2022-03-30 02:36:31', '2022-03-30 02:36:31', 238, 1, NULL),
(167, 'Av. Tupac Amaru  523 - MZ 16 - LT 3', 'si', 'noble', 1, 4, 14, 'no', 'no', '2022-03-30 02:39:42', '2022-03-30 02:39:42', 239, 1, NULL),
(168, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 1, 4, 'no', 'no', '2022-03-30 02:42:25', '2022-03-30 02:42:25', 241, 1, NULL),
(169, 'Av. Tupac Amaru S/N', 'si', 'adobe', 1, 2, 7, 'no', 'no', '2022-03-30 02:44:57', '2022-03-30 02:44:57', 242, 1, NULL),
(170, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 1, 5, 'no', 'no', '2022-03-30 02:47:41', '2022-03-30 02:47:41', 243, 1, NULL),
(171, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 1, 1, 'si', 'no', '2022-03-30 02:50:33', '2022-03-30 02:50:33', 244, 1, NULL),
(172, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 1, 1, 'no', 'no', '2022-03-30 02:53:04', '2022-03-30 02:53:04', 245, 1, NULL),
(173, 'Av. Tupac Amaru S/N', 'no', 'noble', 0, 0, 0, 'no', 'no', '2022-03-30 02:56:13', '2022-03-30 02:56:13', 246, 1, NULL),
(174, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 1, 6, 'no', 'no', '2022-03-30 02:59:07', '2022-03-30 02:59:07', 247, 1, NULL),
(175, 'Av. Tupac Amaru S/N', 'si', 'noble', 1, 1, 4, 'no', 'no', '2022-03-30 03:01:23', '2022-03-30 03:01:23', 248, 1, NULL),
(176, 'ALGARROBOS S/N', '', '', 0, 0, 0, '', '', '2022-03-30 03:03:43', '2022-04-21 00:12:50', 249, 21, NULL),
(177, 'Av. Tupac Amaru  S/N - MZ 8 - LT 17-A', 'si', 'noble', 1, 1, 1, 'si', 'no', '2022-03-30 03:06:47', '2022-03-30 03:06:47', 250, 1, NULL),
(178, 'Av. Tupac Amaru  529 - MZ 16 - LT 3-C', 'si', 'adobe', 0, 0, 0, 'no', 'no', '2022-03-30 03:14:48', '2022-03-30 03:14:48', 240, 1, NULL),
(179, 'A.v. CESAR VALLEJO 207 - LT 2', 'si', 'noble', 0, 0, 0, 'no', 'no', '2022-03-30 10:25:56', '2022-03-30 10:25:56', 75, 2, NULL),
(180, 'A.v. CESAR VALLEJO 345 - DPTO . B', 'si', NULL, 0, 0, 0, 'no', 'no', '2022-03-30 10:35:52', '2022-03-30 10:35:52', 263, 2, NULL),
(181, 'A.V Cesar Vallejo S/N', 'si', NULL, 0, 0, 0, 'no', 'no', '2022-03-30 10:41:19', '2022-03-30 10:41:19', 264, 2, NULL),
(182, 'A.v. Cesar  Vallejo  S/N', NULL, NULL, 0, 0, 0, 'no', 'no', '2022-03-30 10:51:26', '2022-03-30 10:51:26', 266, 2, NULL),
(183, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, 'no', 'no', '2022-03-30 10:59:09', '2022-03-30 10:59:09', 267, 2, NULL),
(184, 'A.v. CESAR VALLEJO 259', NULL, NULL, 0, 0, 0, 'no', 'no', '2022-03-30 11:03:41', '2022-03-30 11:03:41', 268, 2, NULL),
(185, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:12:42', '2022-03-30 11:12:42', 269, 2, NULL),
(186, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:15:50', '2022-03-30 11:15:50', 270, 2, NULL),
(187, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:19:40', '2022-03-30 11:19:40', 271, 2, NULL),
(188, 'A.v. CESAR VALLEJO S/N - MZ 5 - LT 14', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:25:10', '2022-03-30 11:25:10', 272, 2, NULL),
(189, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:30:56', '2022-03-30 11:30:56', 273, 2, NULL),
(190, 'A.v. CESAR VALLEJO 269 - LT B', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:35:26', '2022-03-30 11:35:26', 274, 2, NULL),
(191, 'A.v. CESAR VALLEJO 269 - LT. C', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:41:17', '2022-03-30 11:41:17', 275, 2, NULL),
(192, 'A.v. CESAR VALLEJO 269 - LT. A', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:46:35', '2022-03-30 11:46:35', 276, 2, NULL),
(193, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:49:58', '2022-03-30 11:49:58', 277, 2, NULL),
(194, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 11:55:25', '2022-03-30 11:55:25', 278, 2, NULL),
(195, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:01:24', '2022-03-30 12:01:24', 279, 2, NULL),
(196, 'A.v. CESAR VALLEJO 319', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:07:43', '2022-03-30 12:07:43', 280, 2, NULL),
(197, 'A.v. CESAR VALLEJO 345 - LT. A', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:12:53', '2022-03-30 12:12:53', 281, 2, NULL),
(198, 'A.v. CESAR VALLEJO 125', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:16:54', '2022-03-30 12:16:54', 282, 2, NULL),
(199, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:21:13', '2022-03-30 12:21:13', 283, 2, NULL),
(200, 'A.v. CESAR VALLEJO 140', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:26:26', '2022-03-30 12:26:26', 284, 2, NULL),
(201, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:32:36', '2022-03-30 12:32:36', 285, 2, NULL),
(202, 'A.v. CESAR VALLEJO 350', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:38:10', '2022-03-30 12:38:10', 286, 2, NULL),
(203, 'A.v. CESAR VALLEJO 246', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:43:03', '2022-03-30 12:43:03', 287, 2, NULL),
(204, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:47:38', '2022-03-30 12:47:38', 288, 2, NULL),
(205, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:50:29', '2022-03-30 12:50:29', 289, 2, NULL),
(206, 'A.v. CESAR VALLEJO S/N - MZ 9 - LT 5', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 12:56:20', '2022-03-30 12:56:20', 290, 2, NULL),
(207, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 13:32:49', '2022-03-30 13:32:49', 291, 2, NULL),
(208, 'A.v. CESAR VALLEJO 390', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 13:37:44', '2022-03-30 13:37:44', 292, 2, NULL),
(209, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 13:42:06', '2022-03-30 13:42:06', 293, 2, NULL),
(210, 'A.v. CESAR VALLEJO S/N - MZ A - LT 8', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 13:47:26', '2022-03-30 13:47:26', 294, 2, NULL),
(211, 'A.v. CESAR VALLEJO 164', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 13:52:15', '2022-03-30 13:52:15', 295, 2, NULL),
(212, 'A.v. CESAR VALLEJO 206', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:00:45', '2022-03-30 14:00:45', 296, 2, NULL),
(213, 'A.v. CESAR VALLEJO 394', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:03:55', '2022-03-30 14:03:55', 297, 2, NULL),
(214, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:08:19', '2022-03-30 14:08:19', 298, 2, NULL),
(215, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:13:23', '2022-03-30 14:13:23', 299, 2, NULL),
(216, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:17:30', '2022-03-30 14:17:30', 300, 2, NULL),
(217, 'A.v. CESAR VALLEJO S/N - MZ 9 - LT 3', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:21:22', '2022-03-30 14:21:22', 301, 2, NULL),
(218, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:25:02', '2022-03-30 14:25:02', 302, 2, NULL),
(219, 'A.v. CESAR VALLEJO 273', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:39:40', '2022-03-30 14:39:40', 303, 2, NULL),
(220, 'A.v. CESAR VALLEJO 318', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:47:46', '2022-03-30 14:47:46', 304, 2, NULL),
(221, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:50:40', '2022-03-30 14:50:40', 305, 2, NULL),
(222, 'A.v. CESAR VALLEJO 372', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 14:57:29', '2022-03-30 14:57:29', 306, 2, NULL),
(223, 'A.v. CESAR VALLEJO 113', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:00:30', '2022-03-30 15:00:30', 307, 2, NULL),
(224, 'A.v. CESAR VALLEJO 307', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:08:07', '2022-03-30 15:08:07', 308, 2, NULL),
(225, 'A.v. CESAR VALLEJO # 156 - MZ 5 - LT 7 B', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:13:30', '2022-03-30 15:13:30', 309, 2, NULL),
(226, 'A.v. CESAR VALLEJO S/N - MZ 5 - LT 18', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:17:27', '2022-03-30 15:17:27', 310, 2, NULL),
(227, 'AV.CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:21:26', '2022-03-30 15:21:26', 311, 2, NULL),
(228, 'A.v. CESAR VALLEJO S/N - MZ 5 - LT 15', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:28:01', '2022-03-30 15:28:01', 312, 2, NULL),
(229, 'A.v. CESAR VALLEJO 124', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:36:38', '2022-03-30 15:36:38', 313, 2, NULL),
(230, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:39:59', '2022-03-30 15:39:59', 314, 2, NULL),
(231, 'A.v. CESAR VALLEJO 148 - MZ 5 - LT 10', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:44:15', '2022-03-30 15:44:15', 315, 2, NULL),
(232, 'A.v. CESAR VALLEJO 207', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:48:19', '2022-03-30 15:48:19', 316, 2, NULL),
(233, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:52:12', '2022-03-30 15:52:12', 317, 2, NULL),
(234, 'A.v. CESAR VALLEJO 127', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 15:57:42', '2022-03-30 15:57:42', 318, 2, NULL),
(235, 'A.v. CESAR VALLEJO 141', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:04:28', '2022-03-30 16:04:28', 319, 2, NULL),
(236, 'A.v. CESAR VALLEJO 157', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:09:33', '2022-03-30 16:09:33', 320, 2, NULL),
(237, 'A.v. CESAR VALLEJO 226', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:13:30', '2022-03-30 16:13:30', 321, 2, NULL),
(238, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:20:18', '2022-03-30 16:20:18', 322, 2, NULL),
(239, 'A.v. CESAR VALLEJO S/N - MZ .C - LT	15', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:33:47', '2022-03-30 16:33:47', 323, 2, NULL),
(240, 'A.v. CESAR VALLEJO S/N - MZ 21 - LT	15', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:39:12', '2022-03-30 16:39:12', 324, 2, NULL),
(241, 'A.v. CESAR VALLEJO 354', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:43:23', '2022-03-30 16:43:23', 325, 2, NULL),
(242, 'A.v. CESAR VALLEJO 270', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:49:11', '2022-03-30 16:49:11', 326, 2, NULL),
(243, 'A.v. CESAR VALLEJO 313', '', '', 0, 0, 0, '', '', '2022-03-30 16:55:07', '2022-04-21 01:12:17', 66, 2, NULL),
(244, 'A.v. CESAR VALLEJO 300', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 16:59:30', '2022-03-30 16:59:30', 328, 2, NULL),
(245, 'A.v. CESAR VALLEJO 250', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 17:04:00', '2022-03-30 17:04:00', 329, 2, NULL),
(246, 'A.v. CESAR VALLEJO 207', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 17:08:59', '2022-03-30 17:08:59', 330, 2, NULL),
(247, 'A.v. CESAR VALLEJO 154', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 17:12:56', '2022-03-30 17:12:56', 331, 2, NULL),
(248, 'A.v. CESAR VALLEJO 261', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 17:16:31', '2022-03-30 17:16:31', 332, 2, NULL),
(249, 'A.v. CESAR VALLEJO 257', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 17:19:32', '2022-03-30 17:19:32', 333, 2, NULL),
(250, 'A.v. CESAR VALLEJO 157', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 17:25:30', '2022-03-30 17:25:30', 334, 2, NULL),
(251, 'A.v. CESAR VALLEJO 210', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-30 17:30:25', '2022-03-30 17:30:25', 335, 2, NULL),
(252, 'A.v. CESAR VALLEJO 159', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 09:42:24', '2022-03-31 09:42:24', 336, 2, NULL),
(253, 'A.v. CESAR VALLEJO 202', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 09:50:19', '2022-03-31 09:50:19', 337, 2, NULL),
(254, 'A.v. CESAR VALLEJO 161', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 09:55:13', '2022-03-31 09:55:13', 338, 2, NULL),
(255, 'A.v. CESAR VALLEJO 162', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:03:01', '2022-03-31 10:03:01', 339, 2, NULL),
(256, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:07:50', '2022-03-31 10:07:50', 340, 2, NULL),
(257, 'A.v. CESAR VALLEJO 313', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:18:15', '2022-03-31 10:18:15', 341, 2, NULL),
(258, 'A.v. CESAR VALLEJO 165', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:34:10', '2022-03-31 10:34:10', 342, 2, NULL),
(259, 'A.v. CESAR VALLEJO 166', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:40:46', '2022-03-31 10:40:46', 343, 2, NULL),
(260, 'A.v. CESAR VALLEJO 167', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:48:41', '2022-03-31 10:48:41', 344, 2, NULL),
(261, 'A.v. CESAR VALLEJO 378', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:52:49', '2022-03-31 10:52:49', 345, 2, NULL),
(262, 'A.v. CESAR VALLEJO 239', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:56:14', '2022-03-31 10:56:14', 346, 2, NULL),
(263, 'A.v. CESAR VALLEJO 249', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 10:59:26', '2022-03-31 10:59:26', 347, 2, NULL),
(264, 'A.v. CESAR VALLEJO 171', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:04:43', '2022-03-31 11:04:43', 348, 2, NULL),
(265, 'A.v. CESAR VALLEJO 366', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:08:28', '2022-03-31 11:08:28', 349, 2, NULL),
(266, 'A.v. CESAR VALLEJO 242', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:12:35', '2022-03-31 11:12:35', 350, 2, NULL),
(267, 'A.v. CESAR VALLEJO 174', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:15:55', '2022-03-31 11:15:55', 351, 2, NULL),
(268, 'A.v. CESAR VALLEJO 207', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:19:41', '2022-03-31 11:19:41', 352, 2, NULL),
(269, 'A.v. CESAR VALLEJO 207', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:25:55', '2022-03-31 11:25:55', 353, 2, NULL),
(270, 'A.v. CESAR VALLEJO 207', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:28:22', '2022-03-31 11:28:22', 354, 2, NULL),
(271, 'A.v. CESAR VALLEJO 180', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:30:43', '2022-03-31 11:30:43', 355, 2, NULL),
(272, 'A.v. CESAR VALLEJO 181', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:36:43', '2022-03-31 11:36:43', 356, 2, NULL),
(273, 'A.v. CESAR VALLEJO 183', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:39:56', '2022-03-31 11:39:56', 357, 2, NULL),
(274, 'MARIANO MELGAR S/N', '', '', 0, 0, 0, '', '', '2022-03-31 11:46:08', '2022-04-07 13:10:10', 358, 13, NULL),
(275, 'A.v. CESAR VALLEJO S/N', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:49:39', '2022-03-31 11:49:39', 359, 2, NULL),
(276, 'A.v. CESAR VALLEJO 238', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:52:14', '2022-03-31 11:52:14', 360, 2, NULL),
(277, 'A.v. CESAR VALLEJO 187', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:54:28', '2022-03-31 11:54:28', 361, 2, NULL),
(278, 'A.v. CESAR VALLEJO 384 - MZ 9 - LT 15', NULL, NULL, 0, 0, 0, NULL, NULL, '2022-03-31 11:57:23', '2022-03-31 11:57:23', 362, 2, NULL),
(279, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:15:13', '2022-03-31 12:15:13', 363, 3, NULL),
(280, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:22:31', '2022-03-31 12:22:31', 364, 3, NULL),
(281, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:26:12', '2022-03-31 12:26:12', 365, 3, NULL),
(282, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:44:48', '2022-03-31 12:44:48', 366, 3, NULL),
(283, 'PROLOG.CÉSAR VALLEJO S/N', '', '', 0, 0, 0, '', '', '2022-03-31 12:48:48', '2022-03-31 14:36:39', 375, 3, NULL),
(284, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:48:49', '2022-03-31 12:48:49', 367, 3, NULL),
(285, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 12:52:23', '2022-03-31 12:52:23', 368, 3, NULL),
(286, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 13:01:23', '2022-03-31 13:01:23', 369, 3, NULL),
(287, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 13:42:39', '2022-03-31 13:42:39', 370, 3, NULL),
(288, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 13:54:39', '2022-03-31 13:54:39', 371, 3, NULL),
(289, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 13:58:00', '2022-03-31 13:58:00', 372, 3, NULL),
(290, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:01:09', '2022-03-31 14:01:09', 373, 3, NULL),
(291, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:05:10', '2022-03-31 14:05:10', 374, 3, NULL),
(292, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:46:26', '2022-03-31 14:46:26', 376, 3, NULL),
(293, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:50:32', '2022-03-31 14:50:32', 377, 3, NULL),
(294, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:56:09', '2022-03-31 14:56:09', 378, 3, NULL),
(295, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:59:59', '2022-03-31 14:59:59', 379, 3, NULL),
(296, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:03:33', '2022-03-31 15:03:33', 380, 3, NULL),
(297, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:08:16', '2022-03-31 15:08:16', 381, 3, NULL),
(298, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:14:11', '2022-03-31 15:14:11', 382, 3, NULL),
(299, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:18:12', '2022-03-31 15:18:12', 383, 3, NULL),
(300, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:22:43', '2022-03-31 15:22:43', 384, 3, NULL),
(301, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:26:05', '2022-03-31 15:26:05', 385, 3, NULL),
(302, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:29:31', '2022-03-31 15:29:31', 386, 3, NULL),
(303, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:35:13', '2022-03-31 15:35:13', 387, 3, NULL),
(304, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:38:29', '2022-03-31 15:38:29', 388, 3, NULL),
(305, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:41:22', '2022-03-31 15:41:22', 389, 3, NULL),
(306, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:44:22', '2022-03-31 15:44:22', 390, 3, NULL),
(307, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:47:43', '2022-03-31 15:47:43', 391, 3, NULL),
(308, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 15:58:46', '2022-03-31 15:58:46', 392, 3, NULL),
(309, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:05:13', '2022-03-31 16:05:13', 393, 3, NULL),
(310, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:07:58', '2022-03-31 16:07:58', 394, 3, NULL),
(311, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:15:25', '2022-03-31 16:15:25', 395, 3, NULL),
(312, 'PROLOG.CÉSAR VALLEJO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:17:50', '2022-03-31 16:17:50', 396, 3, NULL),
(313, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:35:22', '2022-03-31 16:35:22', 397, 4, NULL),
(314, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:39:19', '2022-03-31 16:39:19', 398, 4, NULL),
(315, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:42:06', '2022-03-31 16:42:06', 399, 4, NULL),
(316, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:49:22', '2022-03-31 16:49:22', 400, 4, NULL),
(317, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:54:07', '2022-03-31 16:54:07', 401, 4, NULL),
(318, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 16:57:06', '2022-03-31 16:57:06', 402, 4, NULL),
(319, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:03:44', '2022-03-31 17:03:44', 403, 4, NULL),
(320, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:07:29', '2022-03-31 17:07:29', 404, 4, NULL),
(321, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:22:11', '2022-03-31 17:22:11', 405, 4, NULL),
(322, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:24:05', '2022-03-31 17:24:05', 406, 4, NULL),
(323, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:31:25', '2022-03-31 17:31:25', 407, 4, NULL),
(324, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:34:35', '2022-03-31 17:34:35', 408, 4, NULL),
(325, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:42:10', '2022-03-31 17:42:10', 409, 4, NULL),
(326, 'SAN LUIS S/N', '', '', 0, 0, 0, '', '', '2022-03-31 17:46:19', '2022-04-25 19:57:42', 193, 4, NULL),
(327, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:50:25', '2022-03-31 17:50:25', 411, 4, NULL),
(328, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 17:57:07', '2022-03-31 17:57:07', 412, 4, NULL),
(329, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 09:45:57', '2022-04-01 09:45:57', 413, 4, NULL),
(330, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 09:51:37', '2022-04-01 09:51:37', 414, 4, NULL),
(331, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 09:58:37', '2022-04-01 09:58:37', 415, 4, NULL),
(332, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:03:23', '2022-04-01 10:03:23', 416, 4, NULL),
(333, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:08:21', '2022-04-01 10:08:21', 417, 4, NULL),
(334, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:12:49', '2022-04-01 10:12:49', 418, 4, NULL),
(335, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:17:27', '2022-04-01 10:17:27', 419, 4, NULL),
(336, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:20:27', '2022-04-01 10:20:27', 420, 4, NULL),
(337, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:24:22', '2022-04-01 10:24:22', 421, 4, NULL),
(338, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:31:11', '2022-04-01 10:31:11', 422, 4, NULL),
(339, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:34:22', '2022-04-01 10:34:22', 423, 4, NULL),
(340, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:37:15', '2022-04-01 10:37:15', 424, 4, NULL),
(341, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:41:36', '2022-04-01 10:41:36', 425, 4, NULL),
(342, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:44:47', '2022-04-01 10:44:47', 426, 4, NULL),
(343, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:48:53', '2022-04-01 10:48:53', 427, 4, NULL),
(344, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:52:58', '2022-04-01 10:52:58', 428, 4, NULL),
(345, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 10:57:45', '2022-04-01 10:57:45', 429, 4, NULL),
(346, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:01:06', '2022-04-01 11:01:06', 430, 4, NULL),
(347, 'SAN LUIS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:04:26', '2022-04-01 11:04:26', 431, 4, NULL),
(348, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:12:54', '2022-04-01 11:12:54', 432, 5, NULL),
(349, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:16:31', '2022-04-01 11:16:31', 433, 5, NULL),
(350, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:20:31', '2022-04-01 11:20:31', 434, 5, NULL),
(351, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:24:15', '2022-04-01 11:24:15', 435, 5, NULL),
(352, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:28:15', '2022-04-01 11:28:15', 436, 5, NULL),
(353, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:33:10', '2022-04-01 11:33:10', 437, 5, NULL),
(354, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:37:21', '2022-04-01 11:37:21', 438, 5, NULL),
(355, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:44:45', '2022-04-01 11:44:45', 439, 5, NULL),
(356, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:50:57', '2022-04-01 11:50:57', 440, 5, NULL),
(357, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:54:51', '2022-04-01 11:54:51', 441, 5, NULL),
(358, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 11:59:03', '2022-04-01 11:59:03', 442, 5, NULL),
(359, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:02:57', '2022-04-01 12:02:57', 443, 5, NULL),
(360, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:09:52', '2022-04-01 12:09:52', 444, 5, NULL),
(361, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:14:56', '2022-04-01 12:14:56', 445, 5, NULL),
(362, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:19:33', '2022-04-01 12:19:33', 446, 5, NULL),
(363, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:24:52', '2022-04-01 12:24:52', 447, 5, NULL),
(364, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:28:05', '2022-04-01 12:28:05', 448, 5, NULL),
(365, 'Antigua Panamericana norte s/n', '', '', 0, 0, 0, '', '', '2022-04-01 12:30:55', '2022-04-21 21:27:06', 448, 5, NULL),
(366, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:33:59', '2022-04-01 12:33:59', 450, 5, NULL),
(367, 'Antigua Panamericana norte s/n', '', '', 0, 0, 0, '', '', '2022-04-01 12:40:00', '2022-04-25 21:43:35', 207, 5, NULL),
(368, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:44:02', '2022-04-01 12:44:02', 451, 5, NULL),
(369, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:49:08', '2022-04-01 12:49:08', 453, 5, NULL),
(370, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:52:23', '2022-04-01 12:52:23', 454, 5, NULL),
(371, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:54:47', '2022-04-01 12:54:47', 455, 5, NULL),
(372, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 12:58:15', '2022-04-01 12:58:15', 456, 5, NULL),
(373, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:04:14', '2022-04-01 13:04:14', 457, 5, NULL),
(374, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:08:12', '2022-04-01 13:08:12', 458, 5, NULL),
(375, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:15:27', '2022-04-01 13:15:27', 459, 5, NULL),
(376, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:19:25', '2022-04-01 13:19:25', 460, 5, NULL),
(377, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:21:48', '2022-04-01 13:21:48', 461, 5, NULL),
(378, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:24:41', '2022-04-01 13:24:41', 462, 5, NULL),
(379, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 13:30:53', '2022-04-01 13:30:53', 463, 5, NULL),
(380, 'Antigua Panamericana norte s/n', '', '', 0, 0, 0, '', '', '2022-04-01 14:02:59', '2022-04-25 13:00:31', 47, 5, NULL);
INSERT INTO `PREDIO` (`PRE_CODIGO`, `PRE_DIRECCION`, `PRE_HABITADA`, `PRE_MAT_CONSTRUCCION`, `PRE_PISOS`, `PRE_FAMILIAS`, `PRE_HABITANTES`, `PRE_POZO_TABULAR`, `PRE_PISCINA`, `PRE_CREATED`, `PRE_UPDATED`, `CLI_CODIGO`, `CAL_CODIGO`, `PRE_DELETED`) VALUES
(381, 'Antigua Panamericana norte s/n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:05:17', '2022-04-01 14:05:17', 465, 5, NULL),
(382, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:10:50', '2022-04-01 14:10:50', 466, 6, NULL),
(383, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:14:34', '2022-04-01 14:14:34', 467, 6, NULL),
(384, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:17:51', '2022-04-01 14:17:51', 468, 6, NULL),
(385, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:20:50', '2022-04-01 14:20:50', 469, 6, NULL),
(386, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:24:55', '2022-04-01 14:24:55', 470, 6, NULL),
(387, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:30:34', '2022-04-01 14:30:34', 471, 6, NULL),
(388, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:33:38', '2022-04-01 14:33:38', 472, 6, NULL),
(389, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:36:14', '2022-04-01 14:36:14', 473, 6, NULL),
(390, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:39:14', '2022-04-01 14:39:14', 474, 6, NULL),
(391, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:42:26', '2022-04-01 14:42:26', 475, 6, NULL),
(392, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:44:51', '2022-04-01 14:44:51', 476, 6, NULL),
(393, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:47:51', '2022-04-01 14:47:51', 477, 6, NULL),
(394, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:50:09', '2022-04-01 14:50:09', 478, 6, NULL),
(395, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:54:36', '2022-04-01 14:54:36', 479, 6, NULL),
(396, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:57:16', '2022-04-01 14:57:16', 480, 6, NULL),
(397, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 14:59:19', '2022-04-01 14:59:19', 481, 6, NULL),
(398, 'CAJAMARCA  S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:02:08', '2022-04-01 15:02:08', 482, 6, NULL),
(399, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:43:31', '2022-04-01 15:43:31', 483, 8, NULL),
(400, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:49:03', '2022-04-01 15:49:03', 484, 8, NULL),
(401, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:53:01', '2022-04-01 15:53:01', 485, 8, NULL),
(402, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 15:56:39', '2022-04-01 15:56:39', 486, 8, NULL),
(403, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:00:25', '2022-04-01 16:00:25', 487, 8, NULL),
(404, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:04:02', '2022-04-01 16:04:02', 488, 8, NULL),
(405, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:37:06', '2022-04-01 16:37:06', 489, 8, NULL),
(406, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:40:50', '2022-04-01 16:40:50', 490, 8, NULL),
(407, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:45:25', '2022-04-01 16:45:25', 491, 8, NULL),
(408, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:50:48', '2022-04-01 16:50:48', 492, 8, NULL),
(409, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:53:53', '2022-04-01 16:53:53', 493, 8, NULL),
(410, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 16:57:05', '2022-04-01 16:57:05', 494, 8, NULL),
(411, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:00:10', '2022-04-01 17:00:10', 495, 8, NULL),
(412, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:04:33', '2022-04-01 17:04:33', 496, 8, NULL),
(413, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:14:55', '2022-04-01 17:14:55', 497, 8, NULL),
(414, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:17:59', '2022-04-01 17:17:59', 498, 8, NULL),
(415, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:20:37', '2022-04-01 17:20:37', 499, 8, NULL),
(416, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:23:16', '2022-04-01 17:23:16', 500, 8, NULL),
(417, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:26:23', '2022-04-01 17:26:23', 501, 8, NULL),
(418, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:30:23', '2022-04-01 17:30:23', 502, 8, NULL),
(419, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:33:50', '2022-04-01 17:33:50', 503, 8, NULL),
(420, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:40:40', '2022-04-01 17:40:40', 504, 8, NULL),
(421, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:46:27', '2022-04-01 17:46:27', 505, 8, NULL),
(422, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:52:34', '2022-04-01 17:52:34', 506, 8, NULL),
(423, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:54:38', '2022-04-01 17:54:38', 507, 8, NULL),
(424, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:56:43', '2022-04-01 17:56:43', 508, 8, NULL),
(425, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 17:59:57', '2022-04-01 17:59:57', 509, 8, NULL),
(426, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:05:00', '2022-04-01 18:05:00', 510, 8, NULL),
(427, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:06:29', '2022-04-01 18:06:29', 511, 8, NULL),
(428, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:08:58', '2022-04-01 18:08:58', 512, 8, NULL),
(429, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:10:48', '2022-04-01 18:10:48', 513, 8, NULL),
(430, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:12:33', '2022-04-01 18:12:33', 514, 8, NULL),
(431, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:15:23', '2022-04-01 18:15:23', 515, 8, NULL),
(432, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:17:11', '2022-04-01 18:17:11', 516, 8, NULL),
(433, 'FRANCISCO BOLOGNESI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-01 18:18:54', '2022-04-01 18:18:54', 517, 8, NULL),
(434, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 15:56:39', '2022-04-03 15:56:39', 518, 9, NULL),
(435, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:01:28', '2022-04-03 16:01:28', 519, 9, NULL),
(436, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:06:47', '2022-04-03 16:06:47', 520, 9, NULL),
(437, 'MARIANO MELGAR S/N', '', '', 0, 0, 0, '', '', '2022-04-03 16:10:59', '2022-04-22 18:30:32', 521, 13, NULL),
(438, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:12:50', '2022-04-03 16:12:50', 522, 9, NULL),
(439, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:19:45', '2022-04-03 16:19:45', 523, 9, NULL),
(440, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:24:14', '2022-04-03 16:24:14', 524, 9, NULL),
(441, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:31:54', '2022-04-03 16:31:54', 525, 9, NULL),
(442, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 16:35:03', '2022-04-03 16:35:03', 526, 9, NULL),
(443, 'JOSE OLAYA S/N  -  LT 1', '', '', 0, 0, 0, '', '', '2022-04-03 16:39:04', '2022-04-03 16:48:05', 527, 9, NULL),
(444, 'JOSE OLAYA S/N -  LT 2', '', '', 0, 0, 0, '', '', '2022-04-03 16:44:16', '2022-04-03 16:48:21', 527, 9, NULL),
(445, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:16:54', '2022-04-03 17:16:54', 528, 9, NULL),
(446, 'JOSE OLAYA S/N  -  LT 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:21:53', '2022-04-03 17:21:53', 529, 9, NULL),
(447, 'JOSE OLAYA S/N  -  LT 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:23:55', '2022-04-03 17:23:55', 529, 9, NULL),
(448, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:27:28', '2022-04-03 17:27:28', 530, 9, NULL),
(449, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:31:11', '2022-04-03 17:31:11', 531, 9, NULL),
(450, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:33:59', '2022-04-03 17:33:59', 532, 9, NULL),
(451, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:39:37', '2022-04-03 17:39:37', 533, 9, NULL),
(452, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:51:38', '2022-04-03 17:51:38', 534, 9, NULL),
(453, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:55:28', '2022-04-03 17:55:28', 535, 9, NULL),
(454, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 17:58:29', '2022-04-03 17:58:29', 536, 9, NULL),
(455, 'AV. CESAR VALLEJO', '', '', 0, 0, 0, '', '', '2022-04-03 18:05:42', '2022-04-25 12:33:45', 537, 2, NULL),
(456, 'JOSE OLAYA S/N -  LT 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 18:12:25', '2022-04-03 18:12:25', 538, 9, NULL),
(457, 'JOSE OLAYA S/N  -  LT 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 18:18:23', '2022-04-03 18:18:23', 538, 9, NULL),
(458, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 18:21:01', '2022-04-03 18:21:01', 539, 9, NULL),
(459, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 18:47:31', '2022-04-03 18:47:31', 540, 9, NULL),
(460, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:01:15', '2022-04-03 19:01:15', 541, 9, NULL),
(461, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:09:40', '2022-04-03 19:09:40', 542, 9, NULL),
(462, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:13:11', '2022-04-03 19:13:11', 543, 9, NULL),
(463, 'JOSE OLAYA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:16:28', '2022-04-03 19:16:28', 544, 9, NULL),
(464, 'JOSE OLAYA S/N', '', '', 0, 0, 0, '', '', '2022-04-03 19:19:48', '2022-04-25 14:33:42', 109, 9, NULL),
(465, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:25:59', '2022-04-03 19:25:59', 546, 11, NULL),
(466, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:56:49', '2022-04-03 19:56:49', 547, 11, NULL),
(467, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 19:59:44', '2022-04-03 19:59:44', 548, 11, NULL),
(468, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:02:46', '2022-04-03 20:02:46', 549, 11, NULL),
(469, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:15:19', '2022-04-03 20:15:19', 550, 11, NULL),
(470, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:20:27', '2022-04-03 20:20:27', 551, 11, NULL),
(471, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:23:16', '2022-04-03 20:23:16', 552, 11, NULL),
(472, 'Manuel Pardo S/N  LT 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:26:52', '2022-04-03 20:26:52', 553, 11, NULL),
(473, 'Manuel Pardo S/N  LT 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:28:39', '2022-04-03 20:28:39', 553, 11, NULL),
(474, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:32:11', '2022-04-03 20:32:11', 554, 11, NULL),
(475, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:34:52', '2022-04-03 20:34:52', 555, 11, NULL),
(476, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:37:50', '2022-04-03 20:37:50', 556, 11, NULL),
(477, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:42:41', '2022-04-03 20:42:41', 557, 11, NULL),
(478, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:49:06', '2022-04-03 20:49:06', 558, 11, NULL),
(479, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:56:23', '2022-04-03 20:56:23', 559, 11, NULL),
(480, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 20:59:27', '2022-04-03 20:59:27', 560, 11, NULL),
(481, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:02:57', '2022-04-03 21:02:57', 561, 11, NULL),
(482, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:05:47', '2022-04-03 21:05:47', 562, 11, NULL),
(483, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:08:24', '2022-04-03 21:08:24', 563, 11, NULL),
(484, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:11:17', '2022-04-03 21:11:17', 564, 11, NULL),
(485, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:14:40', '2022-04-03 21:14:40', 565, 11, NULL),
(486, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:17:33', '2022-04-03 21:17:33', 566, 11, NULL),
(487, 'Manuel Pardo S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:19:45', '2022-04-03 21:19:45', 567, 11, NULL),
(488, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:44:31', '2022-04-03 21:44:31', 568, 10, NULL),
(489, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 21:48:35', '2022-04-03 21:48:35', 569, 10, NULL),
(490, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:22:11', '2022-04-03 22:22:11', 570, 10, NULL),
(491, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:25:20', '2022-04-03 22:25:20', 571, 10, NULL),
(492, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:28:31', '2022-04-03 22:28:31', 572, 10, NULL),
(493, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:34:10', '2022-04-03 22:34:10', 573, 10, NULL),
(494, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:37:43', '2022-04-03 22:37:43', 574, 10, NULL),
(495, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:43:37', '2022-04-03 22:43:37', 575, 10, NULL),
(496, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 22:48:58', '2022-04-03 22:48:58', 576, 10, NULL),
(497, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 23:21:34', '2022-04-03 23:21:34', 577, 10, NULL),
(498, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 23:55:53', '2022-04-03 23:55:53', 578, 10, NULL),
(499, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-03 23:59:29', '2022-04-03 23:59:29', 579, 10, NULL),
(500, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:03:25', '2022-04-04 00:03:25', 580, 10, NULL),
(501, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:06:12', '2022-04-04 00:06:12', 581, 10, NULL),
(502, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:09:12', '2022-04-04 00:09:12', 582, 10, NULL),
(503, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:12:14', '2022-04-04 00:12:14', 583, 10, NULL),
(504, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:15:03', '2022-04-04 00:15:03', 584, 10, NULL),
(505, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:20:53', '2022-04-04 00:20:53', 585, 10, NULL),
(506, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:24:18', '2022-04-04 00:24:18', 586, 10, NULL),
(507, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:27:20', '2022-04-04 00:27:20', 587, 10, NULL),
(508, 'JOSE QUIÑONES S/N', '', '', 0, 0, 0, '', '', '2022-04-04 00:31:10', '2022-04-26 02:34:05', 337, 10, NULL),
(509, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:34:33', '2022-04-04 00:34:33', 589, 10, NULL),
(510, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:37:17', '2022-04-04 00:37:17', 590, 10, NULL),
(511, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:41:37', '2022-04-04 00:41:37', 591, 10, NULL),
(512, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:44:20', '2022-04-04 00:44:20', 592, 10, NULL),
(513, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 00:46:43', '2022-04-04 00:46:43', 593, 10, NULL),
(514, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 15:51:03', '2022-04-04 15:51:03', 594, 13, NULL),
(515, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:02:13', '2022-04-04 16:02:13', 595, 13, NULL),
(516, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:11:25', '2022-04-04 16:11:25', 596, 13, NULL),
(517, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:18:29', '2022-04-04 16:18:29', 597, 13, NULL),
(518, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:24:05', '2022-04-04 16:24:05', 598, 13, NULL),
(519, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:29:45', '2022-04-04 16:29:45', 599, 13, NULL),
(520, 'MARIANO MELGAR S/N', '', '', 0, 0, 0, '', '', '2022-04-04 16:44:05', '2022-04-25 12:46:37', 364, 13, NULL),
(521, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:47:30', '2022-04-04 16:47:30', 601, 13, NULL),
(522, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 16:53:39', '2022-04-04 16:53:39', 602, 13, NULL),
(523, 'MARIANO MELGAR S/N', '', '', 0, 0, 0, '', '', '2022-04-04 16:58:32', '2022-04-25 15:53:37', 7, 13, NULL),
(524, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:01:43', '2022-04-04 17:01:43', 604, 13, NULL),
(525, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:09:28', '2022-04-04 17:09:28', 605, 13, NULL),
(526, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:21:22', '2022-04-04 17:21:22', 606, 13, NULL),
(527, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:24:36', '2022-04-04 17:24:36', 607, 13, NULL),
(528, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:27:37', '2022-04-04 17:27:37', 608, 13, NULL),
(529, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:31:27', '2022-04-04 17:31:27', 609, 13, NULL),
(530, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:34:29', '2022-04-04 17:34:29', 610, 13, NULL),
(531, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:38:07', '2022-04-04 17:38:07', 611, 13, NULL),
(532, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:41:26', '2022-04-04 17:41:26', 612, 13, NULL),
(533, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:45:44', '2022-04-04 17:45:44', 613, 13, NULL),
(534, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:54:40', '2022-04-04 17:54:40', 614, 13, NULL),
(535, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 17:59:05', '2022-04-04 17:59:05', 615, 13, NULL),
(536, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:02:18', '2022-04-04 18:02:18', 616, 13, NULL),
(537, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:05:11', '2022-04-04 18:05:11', 617, 13, NULL),
(538, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:08:16', '2022-04-04 18:08:16', 618, 13, NULL),
(539, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:11:45', '2022-04-04 18:11:45', 619, 13, NULL),
(540, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:15:29', '2022-04-04 18:15:29', 620, 13, NULL),
(541, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:18:48', '2022-04-04 18:18:48', 621, 13, NULL),
(542, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:21:10', '2022-04-04 18:21:10', 622, 13, NULL),
(543, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:24:08', '2022-04-04 18:24:08', 623, 13, NULL),
(544, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:26:51', '2022-04-04 18:26:51', 624, 13, NULL),
(545, 'MARIANO MELGAR S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:29:16', '2022-04-04 18:29:16', 625, 13, NULL),
(546, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:45:43', '2022-04-04 18:45:43', 626, 15, NULL),
(547, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:50:00', '2022-04-04 18:50:00', 627, 15, NULL),
(548, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:53:03', '2022-04-04 18:53:03', 628, 15, NULL),
(549, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 18:57:37', '2022-04-04 18:57:37', 629, 15, NULL),
(550, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:00:57', '2022-04-04 19:00:57', 630, 15, NULL),
(551, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:09:18', '2022-04-04 19:09:18', 287, 15, NULL),
(552, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:13:04', '2022-04-04 19:13:04', 631, 15, NULL),
(553, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:16:34', '2022-04-04 19:16:34', 632, 15, NULL),
(554, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:19:37', '2022-04-04 19:19:37', 633, 15, NULL),
(555, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:23:16', '2022-04-04 19:23:16', 634, 15, NULL),
(556, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:26:35', '2022-04-04 19:26:35', 635, 15, NULL),
(557, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:30:38', '2022-04-04 19:30:38', 636, 15, NULL),
(558, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:36:26', '2022-04-04 19:36:26', 637, 15, NULL),
(559, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:39:12', '2022-04-04 19:39:12', 638, 15, NULL),
(560, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:42:34', '2022-04-04 19:42:34', 639, 15, NULL),
(561, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 19:45:51', '2022-04-04 19:45:51', 640, 15, NULL),
(562, 'NUEVO CHOSICA S/N', '', '', 0, 0, 0, '', '', '2022-04-04 19:53:50', '2022-04-04 19:57:32', 641, 16, NULL),
(563, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:00:16', '2022-04-04 20:00:16', 642, 16, NULL),
(564, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:03:47', '2022-04-04 20:03:47', 643, 16, NULL),
(565, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:06:45', '2022-04-04 20:06:45', 644, 16, NULL),
(566, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:11:08', '2022-04-04 20:11:08', 645, 16, NULL),
(567, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:15:54', '2022-04-04 20:15:54', 646, 16, NULL),
(568, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:21:20', '2022-04-04 20:21:20', 647, 16, NULL),
(569, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:25:43', '2022-04-04 20:25:43', 648, 16, NULL),
(570, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:30:22', '2022-04-04 20:30:22', 649, 16, NULL),
(571, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:33:22', '2022-04-04 20:33:22', 650, 16, NULL),
(572, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:37:18', '2022-04-04 20:37:18', 651, 16, NULL),
(573, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:40:50', '2022-04-04 20:40:50', 652, 16, NULL),
(574, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:44:04', '2022-04-04 20:44:04', 653, 16, NULL),
(575, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 20:47:08', '2022-04-04 20:47:08', 654, 16, NULL),
(576, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 21:12:36', '2022-04-04 21:12:36', 655, 17, NULL),
(577, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 21:20:03', '2022-04-04 21:20:03', 656, 17, NULL),
(578, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-04 21:24:26', '2022-04-04 21:24:26', 657, 17, NULL),
(579, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:04:55', '2022-04-05 00:04:55', 658, 17, NULL),
(580, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:13:38', '2022-04-05 00:13:38', 659, 17, NULL),
(581, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:24:04', '2022-04-05 00:24:04', 660, 17, NULL),
(582, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:28:40', '2022-04-05 00:28:40', 661, 17, NULL),
(583, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:42:42', '2022-04-05 00:42:42', 662, 17, NULL),
(584, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:46:25', '2022-04-05 00:46:25', 663, 17, NULL),
(585, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 00:55:50', '2022-04-05 00:55:50', 664, 17, NULL),
(586, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 01:05:31', '2022-04-05 01:05:31', 665, 17, NULL),
(587, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:20:18', '2022-04-05 15:20:18', 666, 17, NULL),
(588, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:23:28', '2022-04-05 15:23:28', 667, 17, NULL),
(589, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:32:11', '2022-04-05 15:32:11', 668, 17, NULL),
(590, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:39:23', '2022-04-05 15:39:23', 669, 17, NULL),
(591, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:47:21', '2022-04-05 15:47:21', 670, 17, NULL),
(592, 'SANTA ANITA S/N', '', '', 0, 0, 0, '', '', '2022-04-05 15:50:35', '2022-04-25 16:30:06', 89, 17, NULL),
(593, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 15:56:25', '2022-04-05 15:56:25', 672, 17, NULL),
(594, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:01:15', '2022-04-05 16:01:15', 673, 17, NULL),
(595, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:06:28', '2022-04-05 16:06:28', 674, 17, NULL),
(596, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:16:57', '2022-04-05 16:16:57', 675, 17, NULL),
(597, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:24:20', '2022-04-05 16:24:20', 676, 17, NULL),
(598, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 16:28:38', '2022-04-05 16:28:38', 677, 17, NULL),
(599, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:11:42', '2022-04-05 17:11:42', 678, 17, NULL),
(600, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:18:02', '2022-04-05 17:18:02', 679, 17, NULL),
(601, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:21:01', '2022-04-05 17:21:01', 680, 17, NULL),
(602, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:23:56', '2022-04-05 17:23:56', 681, 17, NULL),
(603, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:27:26', '2022-04-05 17:27:26', 682, 17, NULL),
(604, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:31:17', '2022-04-05 17:31:17', 683, 17, NULL),
(605, 'SANTA ANITA S/N   - LT 1', '', '', 0, 0, 0, '', '', '2022-04-05 17:35:14', '2022-04-26 02:19:44', 330, 17, NULL),
(606, 'SANTA ANITA S/N   - LT 2', '', '', 0, 0, 0, '', '', '2022-04-05 17:38:14', '2022-04-26 02:20:09', 330, 17, NULL),
(607, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:42:23', '2022-04-05 17:42:23', 685, 17, NULL),
(608, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:45:58', '2022-04-05 17:45:58', 686, 17, NULL),
(609, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:49:56', '2022-04-05 17:49:56', 687, 17, NULL),
(610, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:53:31', '2022-04-05 17:53:31', 688, 17, NULL),
(611, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 17:56:52', '2022-04-05 17:56:52', 689, 17, NULL),
(612, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:03:37', '2022-04-05 18:03:37', 690, 17, NULL),
(613, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:14:09', '2022-04-05 18:14:09', 691, 17, NULL),
(614, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:17:56', '2022-04-05 18:17:56', 692, 17, NULL),
(615, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:20:24', '2022-04-05 18:20:24', 693, 17, NULL),
(616, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:24:59', '2022-04-05 18:24:59', 694, 17, NULL),
(617, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:31:10', '2022-04-05 18:31:10', 695, 17, NULL),
(618, 'SANTA ANITA S/N   - LT 2', '', '', 0, 0, 0, '', '', '2022-04-05 18:36:27', '2022-04-05 18:38:44', 696, 17, NULL),
(619, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:41:34', '2022-04-05 18:41:34', 697, 17, NULL),
(620, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:49:43', '2022-04-05 18:49:43', 698, 17, NULL),
(621, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:52:01', '2022-04-05 18:52:01', 699, 17, NULL),
(622, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:54:53', '2022-04-05 18:54:53', 700, 17, NULL),
(623, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 18:58:13', '2022-04-05 18:58:13', 701, 17, NULL),
(624, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:00:59', '2022-04-05 19:00:59', 702, 17, NULL),
(625, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:10:36', '2022-04-05 19:10:36', 703, 17, NULL),
(626, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:13:58', '2022-04-05 19:13:58', 704, 17, NULL),
(627, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:20:46', '2022-04-05 19:20:46', 705, 17, NULL),
(628, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:24:12', '2022-04-05 19:24:12', 706, 17, NULL),
(629, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 19:39:35', '2022-04-05 19:39:35', 707, 21, NULL),
(630, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:11:13', '2022-04-05 20:11:13', 708, 21, NULL),
(631, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:15:56', '2022-04-05 20:15:56', 709, 21, NULL),
(632, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:19:23', '2022-04-05 20:19:23', 710, 21, NULL),
(633, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:22:29', '2022-04-05 20:22:29', 711, 21, NULL),
(634, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:25:04', '2022-04-05 20:25:04', 712, 21, NULL),
(635, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:52:54', '2022-04-05 20:52:54', 713, 21, NULL),
(636, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 20:57:41', '2022-04-05 20:57:41', 714, 21, NULL),
(637, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:00:39', '2022-04-05 21:00:39', 715, 21, NULL),
(638, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:04:43', '2022-04-05 21:04:43', 716, 21, NULL),
(639, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:08:43', '2022-04-05 21:08:43', 717, 21, NULL),
(640, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:12:18', '2022-04-05 21:12:18', 718, 21, NULL),
(641, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:16:13', '2022-04-05 21:16:13', 719, 21, NULL),
(642, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:21:00', '2022-04-05 21:21:00', 720, 21, NULL),
(643, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:27:19', '2022-04-05 21:27:19', 721, 21, NULL),
(644, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:32:24', '2022-04-05 21:32:24', 722, 21, NULL),
(645, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:35:03', '2022-04-05 21:35:03', 723, 21, NULL),
(646, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:38:14', '2022-04-05 21:38:14', 724, 21, NULL),
(647, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:41:26', '2022-04-05 21:41:26', 725, 21, NULL),
(648, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:44:47', '2022-04-05 21:44:47', 726, 21, NULL),
(649, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:47:48', '2022-04-05 21:47:48', 727, 21, NULL),
(650, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 21:57:19', '2022-04-05 21:57:19', 728, 21, NULL),
(651, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:01:39', '2022-04-05 22:01:39', 729, 21, NULL),
(652, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:04:41', '2022-04-05 22:04:41', 730, 21, NULL),
(653, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:08:35', '2022-04-05 22:08:35', 731, 21, NULL),
(654, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:15:09', '2022-04-05 22:15:09', 732, 21, NULL),
(655, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:18:45', '2022-04-05 22:18:45', 733, 21, NULL),
(656, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:22:36', '2022-04-05 22:22:36', 734, 21, NULL),
(657, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:25:43', '2022-04-05 22:25:43', 735, 21, NULL),
(658, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:28:18', '2022-04-05 22:28:18', 736, 21, NULL),
(659, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:33:27', '2022-04-05 22:33:27', 737, 21, NULL),
(660, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:54:09', '2022-04-05 22:54:09', 738, 21, NULL),
(661, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:57:18', '2022-04-05 22:57:18', 739, 21, NULL),
(662, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 22:59:57', '2022-04-05 22:59:57', 740, 21, NULL),
(663, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:04:43', '2022-04-05 23:04:43', 741, 21, NULL),
(664, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:07:28', '2022-04-05 23:07:28', 742, 21, NULL),
(665, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:13:00', '2022-04-05 23:13:00', 743, 21, NULL),
(666, 'ALGARROBOS S/N - LT 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:16:26', '2022-04-05 23:16:26', 744, 21, NULL),
(667, 'ALGARROBOS S/N - LT 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:18:48', '2022-04-05 23:18:48', 744, 21, NULL),
(668, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:21:18', '2022-04-05 23:21:18', 745, 21, NULL),
(669, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:24:08', '2022-04-05 23:24:08', 746, 21, NULL),
(670, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:28:05', '2022-04-05 23:28:05', 747, 21, NULL),
(671, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:30:35', '2022-04-05 23:30:35', 748, 21, NULL),
(672, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:33:31', '2022-04-05 23:33:31', 749, 21, NULL),
(673, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:36:03', '2022-04-05 23:36:03', 750, 21, NULL),
(674, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:38:55', '2022-04-05 23:38:55', 751, 21, NULL),
(675, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:42:21', '2022-04-05 23:42:21', 752, 21, NULL),
(676, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:44:47', '2022-04-05 23:44:47', 753, 21, NULL),
(677, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:48:31', '2022-04-05 23:48:31', 754, 21, NULL),
(678, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:51:31', '2022-04-05 23:51:31', 755, 21, NULL),
(679, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:53:39', '2022-04-05 23:53:39', 756, 21, NULL),
(680, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:56:03', '2022-04-05 23:56:03', 757, 21, NULL),
(681, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-05 23:58:34', '2022-04-05 23:58:34', 758, 21, NULL),
(682, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 00:03:40', '2022-04-06 00:03:40', 759, 21, NULL),
(683, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 00:06:42', '2022-04-06 00:06:42', 760, 21, NULL),
(684, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 00:09:00', '2022-04-06 00:09:00', 761, 21, NULL),
(685, 'PUENTE GRANDE S/N', '', '', 0, 0, 0, '', '', '2022-04-06 09:47:02', '2022-04-06 10:00:12', 762, 12, NULL),
(686, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:09:06', '2022-04-06 10:09:06', 763, 12, NULL),
(687, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:20:39', '2022-04-06 10:20:39', 764, 12, NULL),
(688, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:24:09', '2022-04-06 10:24:09', 765, 12, NULL),
(689, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:26:56', '2022-04-06 10:26:56', 766, 12, NULL),
(690, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:31:30', '2022-04-06 10:31:30', 767, 12, NULL),
(691, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:37:28', '2022-04-06 10:37:28', 768, 12, NULL),
(692, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:43:25', '2022-04-06 10:43:25', 769, 12, NULL),
(693, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:48:00', '2022-04-06 10:48:00', 770, 12, NULL),
(694, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 10:52:15', '2022-04-06 10:52:15', 771, 12, NULL),
(695, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:00:06', '2022-04-06 11:00:06', 772, 12, NULL),
(696, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:08:08', '2022-04-06 11:08:08', 773, 12, NULL),
(697, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:13:48', '2022-04-06 11:13:48', 774, 12, NULL),
(698, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:20:17', '2022-04-06 11:20:17', 775, 12, NULL),
(699, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:24:36', '2022-04-06 11:24:36', 776, 12, NULL),
(700, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:27:17', '2022-04-06 11:27:17', 777, 12, NULL),
(701, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:29:55', '2022-04-06 11:29:55', 778, 12, NULL),
(702, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:36:22', '2022-04-06 11:36:22', 779, 12, NULL),
(703, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:41:01', '2022-04-06 11:41:01', 780, 12, NULL),
(704, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:47:25', '2022-04-06 11:47:25', 781, 12, NULL),
(705, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:53:04', '2022-04-06 11:53:04', 782, 12, NULL),
(706, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 11:57:04', '2022-04-06 11:57:04', 783, 12, NULL),
(707, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:00:43', '2022-04-06 12:00:43', 784, 12, NULL),
(708, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:06:58', '2022-04-06 12:06:58', 785, 12, NULL),
(709, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:21:45', '2022-04-06 12:21:45', 786, 12, NULL),
(710, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:38:25', '2022-04-06 12:38:25', 787, 12, NULL),
(711, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:43:39', '2022-04-06 12:43:39', 788, 12, NULL),
(712, 'PUENTE GRANDE S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 12:46:09', '2022-04-06 12:46:09', 789, 12, NULL),
(713, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:02:53', '2022-04-06 13:02:53', 790, 20, NULL),
(714, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:17:20', '2022-04-06 13:17:20', 791, 20, NULL),
(715, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:36:02', '2022-04-06 13:36:02', 792, 20, NULL),
(716, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:40:47', '2022-04-06 13:40:47', 793, 20, NULL),
(717, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 13:44:34', '2022-04-06 13:44:34', 794, 20, NULL),
(718, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:26:04', '2022-04-06 14:26:04', 795, 20, NULL),
(719, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:30:04', '2022-04-06 14:30:04', 796, 20, NULL),
(720, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:32:32', '2022-04-06 14:32:32', 797, 20, NULL),
(721, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:36:29', '2022-04-06 14:36:29', 798, 20, NULL),
(722, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:39:57', '2022-04-06 14:39:57', 799, 20, NULL),
(723, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:42:41', '2022-04-06 14:42:41', 800, 20, NULL),
(724, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:46:08', '2022-04-06 14:46:08', 801, 20, NULL),
(725, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:54:44', '2022-04-06 14:54:44', 802, 20, NULL),
(726, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 14:58:12', '2022-04-06 14:58:12', 803, 20, NULL),
(727, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:00:24', '2022-04-06 15:00:24', 804, 20, NULL),
(728, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:03:46', '2022-04-06 15:03:46', 805, 20, NULL),
(729, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:06:32', '2022-04-06 15:06:32', 806, 20, NULL),
(730, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:12:42', '2022-04-06 15:12:42', 807, 20, NULL),
(731, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:16:12', '2022-04-06 15:16:12', 808, 20, NULL),
(732, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:18:30', '2022-04-06 15:18:30', 809, 20, NULL),
(733, 'ANDRES RAZURI S/N', '', '', 0, 0, 0, '', '', '2022-04-06 15:21:23', '2022-04-26 02:56:26', 351, 20, NULL),
(734, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:26:08', '2022-04-06 15:26:08', 811, 20, NULL),
(735, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:28:57', '2022-04-06 15:28:57', 812, 20, NULL),
(736, 'ANDRES RAZURI S/N  -  LT 1', '', '', 0, 0, 0, '', '', '2022-04-06 15:32:30', '2022-04-06 15:33:59', 813, 20, NULL),
(737, 'ANDRES RAZURI S/N  -  LT 2', '', '', 0, 0, 0, '', '', '2022-04-06 15:35:09', '2022-04-07 12:36:56', 813, 20, NULL),
(738, 'ANDRES RAZURI S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:38:34', '2022-04-06 15:38:34', 814, 20, NULL),
(739, 'MIGUEL GRAU S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:44:35', '2022-04-06 15:44:35', 815, 18, NULL),
(740, 'MIGUEL GRAU S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:49:30', '2022-04-06 15:49:30', 816, 18, NULL),
(741, 'MIGUEL GRAU S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:52:32', '2022-04-06 15:52:32', 817, 18, NULL),
(742, 'MIGUEL GRAU S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:54:18', '2022-04-06 15:54:18', 818, 18, NULL),
(743, 'MIGUEL GRAU S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 15:57:10', '2022-04-06 15:57:10', 819, 18, NULL),
(744, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:15:41', '2022-04-06 16:15:41', 820, 19, NULL),
(745, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:25:14', '2022-04-06 16:25:14', 821, 19, NULL),
(746, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:28:55', '2022-04-06 16:28:55', 822, 19, NULL),
(747, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:32:04', '2022-04-06 16:32:04', 823, 19, NULL),
(748, 'PALMO S/N', '', '', 0, 0, 0, '', '', '2022-04-06 16:35:21', '2022-04-06 16:52:05', 826, 19, NULL),
(749, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:35:21', '2022-04-06 16:35:21', 824, 19, NULL),
(750, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:40:11', '2022-04-06 16:40:11', 825, 19, NULL),
(751, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:54:31', '2022-04-06 16:54:31', 827, 19, NULL),
(752, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 16:58:41', '2022-04-06 16:58:41', 828, 19, NULL),
(753, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:01:11', '2022-04-06 17:01:11', 829, 19, NULL),
(754, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:04:02', '2022-04-06 17:04:02', 830, 19, NULL),
(755, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:06:12', '2022-04-06 17:06:12', 831, 19, NULL),
(756, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:08:31', '2022-04-06 17:08:31', 832, 19, NULL),
(757, 'PALMO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:10:45', '2022-04-06 17:10:45', 833, 19, NULL),
(758, 'ONCE FEBRERO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:15:33', '2022-04-06 17:15:33', 834, 14, NULL),
(759, 'ONCE FEBRERO S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:18:32', '2022-04-06 17:18:32', 835, 14, NULL),
(760, 'ONCE FEBRERO S/N', '', '', 0, 0, 0, '', '', '2022-04-06 17:22:12', '2022-04-25 23:52:02', 75, 14, NULL),
(761, 'ARICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:30:42', '2022-04-06 17:30:42', 837, 22, NULL),
(762, 'ARICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:33:07', '2022-04-06 17:33:07', 838, 22, NULL),
(763, 'ARICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:35:04', '2022-04-06 17:35:04', 839, 22, NULL),
(764, 'AV. TUPAC AMARU N° 217  - LT 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:44:36', '2022-04-06 17:44:36', 17, 1, NULL),
(765, 'JOSE QUIÑONES S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 17:56:02', '2022-04-06 17:56:02', 840, 10, NULL),
(766, 'LETICIA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 18:00:12', '2022-04-06 18:00:12', 841, 15, NULL),
(767, 'NUEVO CHOSICA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 18:04:01', '2022-04-06 18:04:01', 842, 16, NULL),
(768, 'SANTA ANITA S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 18:07:24', '2022-04-06 18:07:24', 843, 17, NULL),
(769, 'ALGARROBOS S/N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-06 18:11:03', '2022-04-06 18:11:03', 844, 21, NULL),
(770, 'TRUJILLO S/N - MZ C - LT 42', '', '', 0, 0, 0, '', '', '2022-04-07 10:36:01', '2022-04-07 10:42:41', 845, 7, NULL),
(771, 'TRUJILLO S/N - MZ C - LT 33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:44:11', '2022-04-07 10:44:11', 846, 7, NULL);
INSERT INTO `PREDIO` (`PRE_CODIGO`, `PRE_DIRECCION`, `PRE_HABITADA`, `PRE_MAT_CONSTRUCCION`, `PRE_PISOS`, `PRE_FAMILIAS`, `PRE_HABITANTES`, `PRE_POZO_TABULAR`, `PRE_PISCINA`, `PRE_CREATED`, `PRE_UPDATED`, `CLI_CODIGO`, `CAL_CODIGO`, `PRE_DELETED`) VALUES
(772, 'TRUJILLO S/N - MZ C - LT 36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:46:15', '2022-04-07 10:46:15', 847, 7, NULL),
(773, 'TRUJILLO S/N - MZ C - LT 32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:48:46', '2022-04-07 10:48:46', 848, 7, NULL),
(774, 'TRUJILLO S/N - MZ C - LT 38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:54:37', '2022-04-07 10:54:37', 849, 7, NULL),
(775, 'TRUJILLO S/N - MZ C - LT 31', '', '', 0, 0, 0, '', '', '2022-04-07 10:56:55', '2022-04-17 22:57:47', 850, 7, NULL),
(776, 'TRUJILLO S/N - MZ C - LT 29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 10:58:34', '2022-04-07 10:58:34', 851, 7, NULL),
(777, 'TRUJILLO S/N - MZ C - LT 47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 11:01:01', '2022-04-07 11:01:01', 852, 7, NULL),
(778, 'TRUJILLO S/N - MZ C - LT 37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 11:43:57', '2022-04-07 11:43:57', 853, 7, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PROYECTO`
--

CREATE TABLE `PROYECTO` (
  `PTO_CODIGO` int NOT NULL,
  `PTO_NOMBRE` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `PTO_MNTO_CTO` double(5,2) NOT NULL,
  `PTO_NUM_CUOTAS` int NOT NULL,
  `PTO_MNTO_TOTAL` double(8,2) NOT NULL,
  `PTO_DESCRIPCION` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `PTO_ESTADO` int NOT NULL,
  `PTO_CREATED` datetime NOT NULL,
  `PTO_UPDATED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RECIBO`
--

CREATE TABLE `RECIBO` (
  `RBO_CODIGO` int NOT NULL,
  `RBO_PERIODO` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `RBO_FEC_PERIODO` date NOT NULL,
  `RBO_ULT_DIA_PAGO` date NOT NULL,
  `RBO_FECHA_CORTE` date DEFAULT NULL,
  `RBO_MNTO_CONSUMO` double(5,2) NOT NULL,
  `RBO_MNTO_SERV_ADI` double(5,2) DEFAULT NULL,
  `RBO_MNTO_FIN_CUOTA` double(5,2) NOT NULL DEFAULT '0.00',
  `RBO_IGV` int NOT NULL,
  `RBO_MNTO_TOTAL` double(5,2) DEFAULT NULL,
  `RBO_ESTADO` int NOT NULL,
  `RBO_CREATED` datetime NOT NULL,
  `RBO_UPDATED` datetime NOT NULL,
  `FCU_CODIGO` int DEFAULT NULL,
  `CTO_CODIGO` int NOT NULL,
  `FTO_CODIGO` int DEFAULT NULL,
  `IGR_CODIGO` int DEFAULT NULL,
  `RBO_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `RECIBO`
--

INSERT INTO `RECIBO` (`RBO_CODIGO`, `RBO_PERIODO`, `RBO_FEC_PERIODO`, `RBO_ULT_DIA_PAGO`, `RBO_FECHA_CORTE`, `RBO_MNTO_CONSUMO`, `RBO_MNTO_SERV_ADI`, `RBO_MNTO_FIN_CUOTA`, `RBO_IGV`, `RBO_MNTO_TOTAL`, `RBO_ESTADO`, `RBO_CREATED`, `RBO_UPDATED`, `FCU_CODIGO`, `CTO_CODIGO`, `FTO_CODIGO`, `IGR_CODIGO`, `RBO_DELETED`) VALUES
(1, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 1, NULL, NULL, NULL),
(2, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 3, NULL, NULL, NULL),
(3, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 4, NULL, NULL, NULL),
(4, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 5, NULL, NULL, NULL),
(5, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 6, NULL, NULL, NULL),
(6, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 7, NULL, NULL, NULL),
(7, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 8, NULL, NULL, NULL),
(8, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 9, NULL, NULL, NULL),
(9, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 10, NULL, NULL, NULL),
(10, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 11, NULL, NULL, NULL),
(11, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 12, NULL, NULL, NULL),
(12, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 13, NULL, NULL, NULL),
(13, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 14, NULL, NULL, NULL),
(14, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 15, NULL, NULL, NULL),
(15, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 16, NULL, NULL, NULL),
(16, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 17, NULL, NULL, NULL),
(17, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 18, NULL, NULL, NULL),
(18, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 19, NULL, NULL, NULL),
(19, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 20, NULL, NULL, NULL),
(20, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 21, NULL, NULL, NULL),
(21, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 22, NULL, NULL, NULL),
(22, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 23, NULL, NULL, NULL),
(23, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 24, NULL, NULL, NULL),
(24, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 25, NULL, NULL, NULL),
(25, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 26, NULL, NULL, NULL),
(26, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 27, NULL, NULL, NULL),
(27, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 28, NULL, NULL, NULL),
(28, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 29, NULL, NULL, NULL),
(29, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 30, NULL, NULL, NULL),
(30, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 31, NULL, NULL, NULL),
(31, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 32, NULL, NULL, NULL),
(32, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 33, NULL, NULL, NULL),
(33, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 34, NULL, NULL, NULL),
(34, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 35, NULL, NULL, NULL),
(35, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 36, NULL, NULL, NULL),
(36, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 37, NULL, NULL, NULL),
(37, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 38, NULL, NULL, NULL),
(38, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 39, NULL, NULL, NULL),
(39, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 40, NULL, NULL, NULL),
(40, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 41, NULL, NULL, NULL),
(41, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 42, NULL, NULL, NULL),
(42, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 43, NULL, NULL, NULL),
(43, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 44, NULL, NULL, NULL),
(44, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 45, NULL, NULL, NULL),
(45, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 46, NULL, NULL, NULL),
(46, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 47, NULL, NULL, NULL),
(47, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 48, NULL, NULL, NULL),
(48, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 49, NULL, NULL, NULL),
(49, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 50, NULL, NULL, NULL),
(50, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 51, NULL, NULL, NULL),
(51, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 52, NULL, NULL, NULL),
(52, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 53, NULL, NULL, NULL),
(53, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 54, NULL, NULL, NULL),
(54, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 55, NULL, NULL, NULL),
(55, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 56, NULL, NULL, NULL),
(56, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 57, NULL, NULL, NULL),
(57, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 58, NULL, NULL, NULL),
(58, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 59, NULL, NULL, NULL),
(59, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 60, NULL, NULL, NULL),
(60, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 61, NULL, NULL, NULL),
(61, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 62, NULL, NULL, NULL),
(62, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 63, NULL, NULL, NULL),
(63, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 64, NULL, NULL, NULL),
(64, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 65, NULL, NULL, NULL),
(65, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 66, NULL, NULL, NULL),
(66, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 67, NULL, NULL, NULL),
(67, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 68, NULL, NULL, NULL),
(68, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 69, NULL, NULL, NULL),
(69, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 70, NULL, NULL, NULL),
(70, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 71, NULL, NULL, NULL),
(71, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 72, NULL, NULL, NULL),
(72, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 73, NULL, NULL, NULL),
(73, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 74, NULL, NULL, NULL),
(74, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 75, NULL, NULL, NULL),
(75, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 76, NULL, NULL, NULL),
(76, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 77, NULL, NULL, NULL),
(77, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 78, NULL, NULL, NULL),
(78, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 79, NULL, NULL, NULL),
(79, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 80, NULL, NULL, NULL),
(80, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 81, NULL, NULL, NULL),
(81, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 82, NULL, NULL, NULL),
(82, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 83, NULL, NULL, NULL),
(83, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 84, NULL, NULL, NULL),
(84, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 85, NULL, NULL, NULL),
(85, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 86, NULL, NULL, NULL),
(86, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 87, NULL, NULL, NULL),
(87, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 88, NULL, NULL, NULL),
(88, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 89, NULL, NULL, NULL),
(89, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 90, NULL, NULL, NULL),
(90, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 91, NULL, NULL, NULL),
(91, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 92, NULL, NULL, NULL),
(92, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 93, NULL, NULL, NULL),
(93, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 94, NULL, NULL, NULL),
(94, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 95, NULL, NULL, NULL),
(95, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 96, NULL, NULL, NULL),
(96, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 97, NULL, NULL, NULL),
(97, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 98, NULL, NULL, NULL),
(98, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 99, NULL, NULL, NULL),
(99, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 100, NULL, NULL, NULL),
(100, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 101, NULL, NULL, NULL),
(101, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 120.00, 0.00, 0.00, 18, 141.60, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 102, NULL, NULL, NULL),
(102, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 103, NULL, NULL, NULL),
(103, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 104, NULL, NULL, NULL),
(104, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 105, NULL, NULL, NULL),
(105, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 106, NULL, NULL, NULL),
(106, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 107, NULL, NULL, NULL),
(107, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 108, NULL, NULL, NULL),
(108, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 109, NULL, NULL, NULL),
(109, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 110, NULL, NULL, NULL),
(110, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 111, NULL, NULL, NULL),
(111, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 112, NULL, NULL, NULL),
(112, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 113, NULL, NULL, NULL),
(113, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 114, NULL, NULL, NULL),
(114, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 115, NULL, NULL, NULL),
(115, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 116, NULL, NULL, NULL),
(116, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 117, NULL, NULL, NULL),
(117, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 118, NULL, NULL, NULL),
(118, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 250.00, 0.00, 0.00, 18, 295.00, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 119, NULL, NULL, NULL),
(119, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 250.00, 0.00, 0.00, 18, 295.00, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 120, NULL, NULL, NULL),
(120, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 121, NULL, NULL, NULL),
(121, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 122, NULL, NULL, NULL),
(122, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 123, NULL, NULL, NULL),
(123, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 124, NULL, NULL, NULL),
(124, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 125, NULL, NULL, NULL),
(125, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 126, NULL, NULL, NULL),
(126, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 127, NULL, NULL, NULL),
(127, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 128, NULL, NULL, NULL),
(128, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 129, NULL, NULL, NULL),
(129, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 130, NULL, NULL, NULL),
(130, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 131, NULL, NULL, NULL),
(131, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 132, NULL, NULL, NULL),
(132, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 94.40, 0.00, 0.00, 18, 111.39, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 133, NULL, NULL, NULL),
(133, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 94.40, 0.00, 0.00, 18, 111.39, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 134, NULL, NULL, NULL),
(134, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 35.00, 0.00, 0.00, 18, 41.30, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 135, NULL, NULL, NULL),
(135, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 136, NULL, NULL, NULL),
(136, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 137, NULL, NULL, NULL),
(137, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 138, NULL, NULL, NULL),
(138, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 139, NULL, NULL, NULL),
(139, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 140, NULL, NULL, NULL),
(140, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 141, NULL, NULL, NULL),
(141, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 142, NULL, NULL, NULL),
(142, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 143, NULL, NULL, NULL),
(143, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 144, NULL, NULL, NULL),
(144, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 145, NULL, NULL, NULL),
(145, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 146, NULL, NULL, NULL),
(146, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 147, NULL, NULL, NULL),
(147, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 80.00, 0.00, 0.00, 18, 94.40, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 148, NULL, NULL, NULL),
(148, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 149, NULL, NULL, NULL),
(149, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 150, NULL, NULL, NULL),
(150, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 151, NULL, NULL, NULL),
(151, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 120.00, 0.00, 0.00, 18, 141.60, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 152, NULL, NULL, NULL),
(152, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 153, NULL, NULL, NULL),
(153, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 154, NULL, NULL, NULL),
(154, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 155, NULL, NULL, NULL),
(155, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 156, NULL, NULL, NULL),
(156, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 157, NULL, NULL, NULL),
(157, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 158, NULL, NULL, NULL),
(158, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 159, NULL, NULL, NULL),
(159, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 120.00, 0.00, 0.00, 18, 141.60, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 160, NULL, NULL, NULL),
(160, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 161, NULL, NULL, NULL),
(161, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 120.00, 0.00, 0.00, 18, 141.60, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 162, NULL, NULL, NULL),
(162, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 163, NULL, NULL, NULL),
(163, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 120.00, 0.00, 0.00, 18, 141.60, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 164, NULL, NULL, NULL),
(164, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 120.00, 0.00, 0.00, 18, 141.60, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 165, NULL, NULL, NULL),
(165, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 166, NULL, NULL, NULL),
(166, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 167, NULL, NULL, NULL),
(167, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 168, NULL, NULL, NULL),
(168, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 169, NULL, NULL, NULL),
(169, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 170, NULL, NULL, NULL),
(170, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 171, NULL, NULL, NULL),
(171, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 172, NULL, NULL, NULL),
(172, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 173, NULL, NULL, NULL),
(173, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 174, NULL, NULL, NULL),
(174, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 175, NULL, NULL, NULL),
(175, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 176, NULL, NULL, NULL),
(176, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 177, NULL, NULL, NULL),
(177, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 178, NULL, NULL, NULL),
(178, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 179, NULL, NULL, NULL),
(179, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 180, NULL, NULL, NULL),
(180, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 181, NULL, NULL, NULL),
(181, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 182, NULL, NULL, NULL),
(182, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 183, NULL, NULL, NULL),
(183, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 184, NULL, NULL, NULL),
(184, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 185, NULL, NULL, NULL),
(185, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 186, NULL, NULL, NULL),
(186, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 187, NULL, NULL, NULL),
(187, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 188, NULL, NULL, NULL),
(188, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 189, NULL, NULL, NULL),
(189, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 190, NULL, NULL, NULL),
(190, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 191, NULL, NULL, NULL),
(191, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 192, NULL, NULL, NULL),
(192, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 193, NULL, NULL, NULL),
(193, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 194, NULL, NULL, NULL),
(194, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 195, NULL, NULL, NULL),
(195, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 196, NULL, NULL, NULL),
(196, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 197, NULL, NULL, NULL),
(197, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 198, NULL, NULL, NULL),
(198, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 199, NULL, NULL, NULL),
(199, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 200, NULL, NULL, NULL),
(200, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 201, NULL, NULL, NULL),
(201, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 202, NULL, NULL, NULL),
(202, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 203, NULL, NULL, NULL),
(203, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 204, NULL, NULL, NULL),
(204, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 205, NULL, NULL, NULL),
(205, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 206, NULL, NULL, NULL),
(206, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 207, NULL, NULL, NULL),
(207, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 208, NULL, NULL, NULL),
(208, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 209, NULL, NULL, NULL),
(209, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 210, NULL, NULL, NULL),
(210, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 211, NULL, NULL, NULL),
(211, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 212, NULL, NULL, NULL),
(212, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 213, NULL, NULL, NULL),
(213, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 214, NULL, NULL, NULL),
(214, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 215, NULL, NULL, NULL),
(215, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 216, NULL, NULL, NULL),
(216, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 217, NULL, NULL, NULL),
(217, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 218, NULL, NULL, NULL),
(218, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 219, NULL, NULL, NULL),
(219, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 220, NULL, NULL, NULL),
(220, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 221, NULL, NULL, NULL),
(221, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 222, NULL, NULL, NULL),
(222, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 223, NULL, NULL, NULL),
(223, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 224, NULL, NULL, NULL),
(224, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 225, NULL, NULL, NULL),
(225, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 226, NULL, NULL, NULL),
(226, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 227, NULL, NULL, NULL),
(227, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 228, NULL, NULL, NULL),
(228, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 229, NULL, NULL, NULL),
(229, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 230, NULL, NULL, NULL),
(230, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 231, NULL, NULL, NULL),
(231, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 232, NULL, NULL, NULL),
(232, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 233, NULL, NULL, NULL),
(233, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 234, NULL, NULL, NULL),
(234, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 235, NULL, NULL, NULL),
(235, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 236, NULL, NULL, NULL),
(236, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 237, NULL, NULL, NULL),
(237, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 238, NULL, NULL, NULL),
(238, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 239, NULL, NULL, NULL),
(239, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 240, NULL, NULL, NULL),
(240, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 241, NULL, NULL, NULL),
(241, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 242, NULL, NULL, NULL),
(242, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 243, NULL, NULL, NULL),
(243, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 244, NULL, NULL, NULL),
(244, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 245, NULL, NULL, NULL),
(245, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 246, NULL, NULL, NULL),
(246, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 247, NULL, NULL, NULL),
(247, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 248, NULL, NULL, NULL),
(248, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 249, NULL, NULL, NULL),
(249, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 250, NULL, NULL, NULL),
(250, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 251, NULL, NULL, NULL),
(251, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 252, NULL, NULL, NULL),
(252, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 253, NULL, NULL, NULL),
(253, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 254, NULL, NULL, NULL),
(254, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 255, NULL, NULL, NULL),
(255, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 256, NULL, NULL, NULL),
(256, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 257, NULL, NULL, NULL),
(257, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 258, NULL, NULL, NULL),
(258, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 259, NULL, NULL, NULL),
(259, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 260, NULL, NULL, NULL),
(260, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 261, NULL, NULL, NULL),
(261, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 262, NULL, NULL, NULL),
(262, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 263, NULL, NULL, NULL),
(263, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 264, NULL, NULL, NULL),
(264, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 265, NULL, NULL, NULL),
(265, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 266, NULL, NULL, NULL),
(266, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 267, NULL, NULL, NULL),
(267, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 268, NULL, NULL, NULL),
(268, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 269, NULL, NULL, NULL),
(269, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 270, NULL, NULL, NULL),
(270, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 271, NULL, NULL, NULL),
(271, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 272, NULL, NULL, NULL),
(272, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 273, NULL, NULL, NULL),
(273, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 274, NULL, NULL, NULL),
(274, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 275, NULL, NULL, NULL),
(275, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 276, NULL, NULL, NULL),
(276, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 277, NULL, NULL, NULL),
(277, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 278, NULL, NULL, NULL),
(278, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 279, NULL, NULL, NULL),
(279, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 280, NULL, NULL, NULL),
(280, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 281, NULL, NULL, NULL),
(281, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 282, NULL, NULL, NULL),
(282, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 283, NULL, NULL, NULL),
(283, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 284, NULL, NULL, NULL),
(284, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 285, NULL, NULL, NULL),
(285, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 286, NULL, NULL, NULL),
(286, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 287, NULL, NULL, NULL),
(287, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 288, NULL, NULL, NULL),
(288, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 289, NULL, NULL, NULL),
(289, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 290, NULL, NULL, NULL),
(290, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 291, NULL, NULL, NULL),
(291, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 292, NULL, NULL, NULL),
(292, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 293, NULL, NULL, NULL),
(293, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 294, NULL, NULL, NULL),
(294, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 295, NULL, NULL, NULL),
(295, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 296, NULL, NULL, NULL),
(296, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 297, NULL, NULL, NULL),
(297, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 298, NULL, NULL, NULL),
(298, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 299, NULL, NULL, NULL),
(299, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 300, NULL, NULL, NULL),
(300, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 301, NULL, NULL, NULL),
(301, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 302, NULL, NULL, NULL),
(302, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 303, NULL, NULL, NULL),
(303, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 304, NULL, NULL, NULL),
(304, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 305, NULL, NULL, NULL),
(305, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 306, NULL, NULL, NULL),
(306, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 307, NULL, NULL, NULL);
INSERT INTO `RECIBO` (`RBO_CODIGO`, `RBO_PERIODO`, `RBO_FEC_PERIODO`, `RBO_ULT_DIA_PAGO`, `RBO_FECHA_CORTE`, `RBO_MNTO_CONSUMO`, `RBO_MNTO_SERV_ADI`, `RBO_MNTO_FIN_CUOTA`, `RBO_IGV`, `RBO_MNTO_TOTAL`, `RBO_ESTADO`, `RBO_CREATED`, `RBO_UPDATED`, `FCU_CODIGO`, `CTO_CODIGO`, `FTO_CODIGO`, `IGR_CODIGO`, `RBO_DELETED`) VALUES
(307, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 308, NULL, NULL, NULL),
(308, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 309, NULL, NULL, NULL),
(309, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 310, NULL, NULL, NULL),
(310, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 311, NULL, NULL, NULL),
(311, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 312, NULL, NULL, NULL),
(312, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 313, NULL, NULL, NULL),
(313, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 314, NULL, NULL, NULL),
(314, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 315, NULL, NULL, NULL),
(315, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 316, NULL, NULL, NULL),
(316, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 317, NULL, NULL, NULL),
(317, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 318, NULL, NULL, NULL),
(318, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 319, NULL, NULL, NULL),
(319, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 320, NULL, NULL, NULL),
(320, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 321, NULL, NULL, NULL),
(321, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 322, NULL, NULL, NULL),
(322, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 323, NULL, NULL, NULL),
(323, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 324, NULL, NULL, NULL),
(324, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 325, NULL, NULL, NULL),
(325, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 326, NULL, NULL, NULL),
(326, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 327, NULL, NULL, NULL),
(327, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 328, NULL, NULL, NULL),
(328, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 329, NULL, NULL, NULL),
(329, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 330, NULL, NULL, NULL),
(330, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 331, NULL, NULL, NULL),
(331, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 332, NULL, NULL, NULL),
(332, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 333, NULL, NULL, NULL),
(333, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 334, NULL, NULL, NULL),
(334, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 335, NULL, NULL, NULL),
(335, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 336, NULL, NULL, NULL),
(336, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 337, NULL, NULL, NULL),
(337, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 338, NULL, NULL, NULL),
(338, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 339, NULL, NULL, NULL),
(339, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 340, NULL, NULL, NULL),
(340, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 341, NULL, NULL, NULL),
(341, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 342, NULL, NULL, NULL),
(342, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 343, NULL, NULL, NULL),
(343, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 344, NULL, NULL, NULL),
(344, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 345, NULL, NULL, NULL),
(345, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 346, NULL, NULL, NULL),
(346, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 347, NULL, NULL, NULL),
(347, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 348, NULL, NULL, NULL),
(348, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 349, NULL, NULL, NULL),
(349, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 350, NULL, NULL, NULL),
(350, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 351, NULL, NULL, NULL),
(351, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 352, NULL, NULL, NULL),
(352, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 353, NULL, NULL, NULL),
(353, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 354, NULL, NULL, NULL),
(354, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 355, NULL, NULL, NULL),
(355, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 356, NULL, NULL, NULL),
(356, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 357, NULL, NULL, NULL),
(357, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 358, NULL, NULL, NULL),
(358, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 359, NULL, NULL, NULL),
(359, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 360, NULL, NULL, NULL),
(360, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 361, NULL, NULL, NULL),
(361, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 362, NULL, NULL, NULL),
(362, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 363, NULL, NULL, NULL),
(363, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 364, NULL, NULL, NULL),
(364, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 365, NULL, NULL, NULL),
(365, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 366, NULL, NULL, NULL),
(366, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 367, NULL, NULL, NULL),
(367, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 368, NULL, NULL, NULL),
(368, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 369, NULL, NULL, NULL),
(369, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 370, NULL, NULL, NULL),
(370, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 371, NULL, NULL, NULL),
(371, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 372, NULL, NULL, NULL),
(372, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 373, NULL, NULL, NULL),
(373, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 374, NULL, NULL, NULL),
(374, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 375, NULL, NULL, NULL),
(375, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 376, NULL, NULL, NULL),
(376, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 377, NULL, NULL, NULL),
(377, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 378, NULL, NULL, NULL),
(378, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 379, NULL, NULL, NULL),
(379, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 380, NULL, NULL, NULL),
(380, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 381, NULL, NULL, NULL),
(381, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 382, NULL, NULL, NULL),
(382, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 383, NULL, NULL, NULL),
(383, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 384, NULL, NULL, NULL),
(384, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 385, NULL, NULL, NULL),
(385, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 386, NULL, NULL, NULL),
(386, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 387, NULL, NULL, NULL),
(387, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 388, NULL, NULL, NULL),
(388, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 389, NULL, NULL, NULL),
(389, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 390, NULL, NULL, NULL),
(390, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 391, NULL, NULL, NULL),
(391, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 392, NULL, NULL, NULL),
(392, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 393, NULL, NULL, NULL),
(393, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 394, NULL, NULL, NULL),
(394, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 395, NULL, NULL, NULL),
(395, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 396, NULL, NULL, NULL),
(396, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 397, NULL, NULL, NULL),
(397, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 398, NULL, NULL, NULL),
(398, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 399, NULL, NULL, NULL),
(399, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 400, NULL, NULL, NULL),
(400, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 401, NULL, NULL, NULL),
(401, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 402, NULL, NULL, NULL),
(402, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 403, NULL, NULL, NULL),
(403, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 404, NULL, NULL, NULL),
(404, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 405, NULL, NULL, NULL),
(405, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 406, NULL, NULL, NULL),
(406, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 407, NULL, NULL, NULL),
(407, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 408, NULL, NULL, NULL),
(408, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 409, NULL, NULL, NULL),
(409, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 410, NULL, NULL, NULL),
(410, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 411, NULL, NULL, NULL),
(411, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 412, NULL, NULL, NULL),
(412, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 413, NULL, NULL, NULL),
(413, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 414, NULL, NULL, NULL),
(414, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 415, NULL, NULL, NULL),
(415, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 416, NULL, NULL, NULL),
(416, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 417, NULL, NULL, NULL),
(417, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 418, NULL, NULL, NULL),
(418, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 419, NULL, NULL, NULL),
(419, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 420, NULL, NULL, NULL),
(420, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 421, NULL, NULL, NULL),
(421, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 422, NULL, NULL, NULL),
(422, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 423, NULL, NULL, NULL),
(423, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 424, NULL, NULL, NULL),
(424, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 425, NULL, NULL, NULL),
(425, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 426, NULL, NULL, NULL),
(426, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 427, NULL, NULL, NULL),
(427, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 428, NULL, NULL, NULL),
(428, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 429, NULL, NULL, NULL),
(429, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 430, NULL, NULL, NULL),
(430, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 431, NULL, NULL, NULL),
(431, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 432, NULL, NULL, NULL),
(432, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 433, NULL, NULL, NULL),
(433, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 434, NULL, NULL, NULL),
(434, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 435, NULL, NULL, NULL),
(435, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 436, NULL, NULL, NULL),
(436, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 437, NULL, NULL, NULL),
(437, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 438, NULL, NULL, NULL),
(438, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 439, NULL, NULL, NULL),
(439, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 440, NULL, NULL, NULL),
(440, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 441, NULL, NULL, NULL),
(441, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 442, NULL, NULL, NULL),
(442, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 443, NULL, NULL, NULL),
(443, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 444, NULL, NULL, NULL),
(444, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 445, NULL, NULL, NULL),
(445, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 446, NULL, NULL, NULL),
(446, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 447, NULL, NULL, NULL),
(447, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 448, NULL, NULL, NULL),
(448, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 449, NULL, NULL, NULL),
(449, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 450, NULL, NULL, NULL),
(450, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 451, NULL, NULL, NULL),
(451, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 452, NULL, NULL, NULL),
(452, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 453, NULL, NULL, NULL),
(453, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 454, NULL, NULL, NULL),
(454, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 455, NULL, NULL, NULL),
(455, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 456, NULL, NULL, NULL),
(456, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 457, NULL, NULL, NULL),
(457, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 458, NULL, NULL, NULL),
(458, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 459, NULL, NULL, NULL),
(459, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 460, NULL, NULL, NULL),
(460, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 461, NULL, NULL, NULL),
(461, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 462, NULL, NULL, NULL),
(462, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 463, NULL, NULL, NULL),
(463, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 464, NULL, NULL, NULL),
(464, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 465, NULL, NULL, NULL),
(465, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 466, NULL, NULL, NULL),
(466, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 467, NULL, NULL, NULL),
(467, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 468, NULL, NULL, NULL),
(468, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 469, NULL, NULL, NULL),
(469, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 470, NULL, NULL, NULL),
(470, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 471, NULL, NULL, NULL),
(471, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 472, NULL, NULL, NULL),
(472, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 473, NULL, NULL, NULL),
(473, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 474, NULL, NULL, NULL),
(474, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 475, NULL, NULL, NULL),
(475, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 476, NULL, NULL, NULL),
(476, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 477, NULL, NULL, NULL),
(477, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 478, NULL, NULL, NULL),
(478, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 479, NULL, NULL, NULL),
(479, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 480, NULL, NULL, NULL),
(480, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 481, NULL, NULL, NULL),
(481, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 482, NULL, NULL, NULL),
(482, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 483, NULL, NULL, NULL),
(483, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 484, NULL, NULL, NULL),
(484, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 485, NULL, NULL, NULL),
(485, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 486, NULL, NULL, NULL),
(486, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 487, NULL, NULL, NULL),
(487, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 488, NULL, NULL, NULL),
(488, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 489, NULL, NULL, NULL),
(489, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 490, NULL, NULL, NULL),
(490, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 491, NULL, NULL, NULL),
(491, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 492, NULL, NULL, NULL),
(492, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 493, NULL, NULL, NULL),
(493, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 494, NULL, NULL, NULL),
(494, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 495, NULL, NULL, NULL),
(495, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 496, NULL, NULL, NULL),
(496, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 497, NULL, NULL, NULL),
(497, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 498, NULL, NULL, NULL),
(498, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 499, NULL, NULL, NULL),
(499, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 500, NULL, NULL, NULL),
(500, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 501, NULL, NULL, NULL),
(501, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 502, NULL, NULL, NULL),
(502, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 503, NULL, NULL, NULL),
(503, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 504, NULL, NULL, NULL),
(504, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 505, NULL, NULL, NULL),
(505, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 506, NULL, NULL, NULL),
(506, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 507, NULL, NULL, NULL),
(507, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 508, NULL, NULL, NULL),
(508, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 509, NULL, NULL, NULL),
(509, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 510, NULL, NULL, NULL),
(510, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 511, NULL, NULL, NULL),
(511, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 512, NULL, NULL, NULL),
(512, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 513, NULL, NULL, NULL),
(513, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 514, NULL, NULL, NULL),
(514, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 515, NULL, NULL, NULL),
(515, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 516, NULL, NULL, NULL),
(516, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 517, NULL, NULL, NULL),
(517, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 518, NULL, NULL, NULL),
(518, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 519, NULL, NULL, NULL),
(519, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 520, NULL, NULL, NULL),
(520, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 521, NULL, NULL, NULL),
(521, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 522, NULL, NULL, NULL),
(522, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 523, NULL, NULL, NULL),
(523, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 524, NULL, NULL, NULL),
(524, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 525, NULL, NULL, NULL),
(525, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 526, NULL, NULL, NULL),
(526, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 527, NULL, NULL, NULL),
(527, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 528, NULL, NULL, NULL),
(528, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 529, NULL, NULL, NULL),
(529, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 530, NULL, NULL, NULL),
(530, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 531, NULL, NULL, NULL),
(531, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 532, NULL, NULL, NULL),
(532, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 533, NULL, NULL, NULL),
(533, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 534, NULL, NULL, NULL),
(534, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 535, NULL, NULL, NULL),
(535, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 536, NULL, NULL, NULL),
(536, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 537, NULL, NULL, NULL),
(537, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 538, NULL, NULL, NULL),
(538, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 539, NULL, NULL, NULL),
(539, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 540, NULL, NULL, NULL),
(540, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 541, NULL, NULL, NULL),
(541, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 542, NULL, NULL, NULL),
(542, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 543, NULL, NULL, NULL),
(543, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 544, NULL, NULL, NULL),
(544, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 545, NULL, NULL, NULL),
(545, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 546, NULL, NULL, NULL),
(546, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 547, NULL, NULL, NULL),
(547, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 548, NULL, NULL, NULL),
(548, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 549, NULL, NULL, NULL),
(549, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 550, NULL, NULL, NULL),
(550, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 551, NULL, NULL, NULL),
(551, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 552, NULL, NULL, NULL),
(552, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 553, NULL, NULL, NULL),
(553, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 554, NULL, NULL, NULL),
(554, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 555, NULL, NULL, NULL),
(555, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 556, NULL, NULL, NULL),
(556, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 557, NULL, NULL, NULL),
(557, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 558, NULL, NULL, NULL),
(558, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 559, NULL, NULL, NULL),
(559, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 560, NULL, NULL, NULL),
(560, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 561, NULL, NULL, NULL),
(561, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 562, NULL, NULL, NULL),
(562, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 563, NULL, NULL, NULL),
(563, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 564, NULL, NULL, NULL),
(564, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 565, NULL, NULL, NULL),
(565, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 566, NULL, NULL, NULL),
(566, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 567, NULL, NULL, NULL),
(567, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 568, NULL, NULL, NULL),
(568, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 569, NULL, NULL, NULL),
(569, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 570, NULL, NULL, NULL),
(570, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 571, NULL, NULL, NULL),
(571, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 572, NULL, NULL, NULL),
(572, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 573, NULL, NULL, NULL),
(573, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 574, NULL, NULL, NULL),
(574, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 575, NULL, NULL, NULL),
(575, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 576, NULL, NULL, NULL),
(576, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 577, NULL, NULL, NULL),
(577, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 578, NULL, NULL, NULL),
(578, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 579, NULL, NULL, NULL),
(579, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 580, NULL, NULL, NULL),
(580, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 581, NULL, NULL, NULL),
(581, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 582, NULL, NULL, NULL),
(582, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 583, NULL, NULL, NULL),
(583, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 584, NULL, NULL, NULL),
(584, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 585, NULL, NULL, NULL),
(585, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 586, NULL, NULL, NULL),
(586, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 587, NULL, NULL, NULL),
(587, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 588, NULL, NULL, NULL),
(588, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 589, NULL, NULL, NULL),
(589, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 590, NULL, NULL, NULL),
(590, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 591, NULL, NULL, NULL),
(591, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 592, NULL, NULL, NULL),
(592, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 593, NULL, NULL, NULL),
(593, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 594, NULL, NULL, NULL),
(594, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 595, NULL, NULL, NULL),
(595, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 596, NULL, NULL, NULL),
(596, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 597, NULL, NULL, NULL),
(597, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 598, NULL, NULL, NULL),
(598, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 599, NULL, NULL, NULL),
(599, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 600, NULL, NULL, NULL),
(600, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 601, NULL, NULL, NULL),
(601, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 602, NULL, NULL, NULL),
(602, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 603, NULL, NULL, NULL),
(603, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 604, NULL, NULL, NULL),
(604, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 605, NULL, NULL, NULL),
(605, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 606, NULL, NULL, NULL),
(606, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 607, NULL, NULL, NULL),
(607, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 608, NULL, NULL, NULL),
(608, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 609, NULL, NULL, NULL),
(609, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 610, NULL, NULL, NULL),
(610, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 611, NULL, NULL, NULL);
INSERT INTO `RECIBO` (`RBO_CODIGO`, `RBO_PERIODO`, `RBO_FEC_PERIODO`, `RBO_ULT_DIA_PAGO`, `RBO_FECHA_CORTE`, `RBO_MNTO_CONSUMO`, `RBO_MNTO_SERV_ADI`, `RBO_MNTO_FIN_CUOTA`, `RBO_IGV`, `RBO_MNTO_TOTAL`, `RBO_ESTADO`, `RBO_CREATED`, `RBO_UPDATED`, `FCU_CODIGO`, `CTO_CODIGO`, `FTO_CODIGO`, `IGR_CODIGO`, `RBO_DELETED`) VALUES
(611, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 612, NULL, NULL, NULL),
(612, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 613, NULL, NULL, NULL),
(613, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 614, NULL, NULL, NULL),
(614, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 616, NULL, NULL, NULL),
(615, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 617, NULL, NULL, NULL),
(616, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 618, NULL, NULL, NULL),
(617, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 619, NULL, NULL, NULL),
(618, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 620, NULL, NULL, NULL),
(619, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 621, NULL, NULL, NULL),
(620, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 622, NULL, NULL, NULL),
(621, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 623, NULL, NULL, NULL),
(622, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 624, NULL, NULL, NULL),
(623, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 625, NULL, NULL, NULL),
(624, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 626, NULL, NULL, NULL),
(625, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 627, NULL, NULL, NULL),
(626, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 628, NULL, NULL, NULL),
(627, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 629, NULL, NULL, NULL),
(628, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 630, NULL, NULL, NULL),
(629, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 631, NULL, NULL, NULL),
(630, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 632, NULL, NULL, NULL),
(631, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 633, NULL, NULL, NULL),
(632, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 634, NULL, NULL, NULL),
(633, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 635, NULL, NULL, NULL),
(634, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 636, NULL, NULL, NULL),
(635, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 637, NULL, NULL, NULL),
(636, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 638, NULL, NULL, NULL),
(637, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 639, NULL, NULL, NULL),
(638, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 640, NULL, NULL, NULL),
(639, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 641, NULL, NULL, NULL),
(640, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 642, NULL, NULL, NULL),
(641, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 643, NULL, NULL, NULL),
(642, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 644, NULL, NULL, NULL),
(643, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 645, NULL, NULL, NULL),
(644, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 646, NULL, NULL, NULL),
(645, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 647, NULL, NULL, NULL),
(646, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 35.00, 0.00, 0.00, 18, 41.30, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 648, NULL, NULL, NULL),
(647, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 649, NULL, NULL, NULL),
(648, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 650, NULL, NULL, NULL),
(649, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 651, NULL, NULL, NULL),
(650, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 652, NULL, NULL, NULL),
(651, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 653, NULL, NULL, NULL),
(652, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 654, NULL, NULL, NULL),
(653, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 655, NULL, NULL, NULL),
(654, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 656, NULL, NULL, NULL),
(655, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 657, NULL, NULL, NULL),
(656, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 658, NULL, NULL, NULL),
(657, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 659, NULL, NULL, NULL),
(658, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 660, NULL, NULL, NULL),
(659, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 661, NULL, NULL, NULL),
(660, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 662, NULL, NULL, NULL),
(661, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 663, NULL, NULL, NULL),
(662, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 664, NULL, NULL, NULL),
(663, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 665, NULL, NULL, NULL),
(664, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 666, NULL, NULL, NULL),
(665, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 667, NULL, NULL, NULL),
(666, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 668, NULL, NULL, NULL),
(667, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 669, NULL, NULL, NULL),
(668, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 670, NULL, NULL, NULL),
(669, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 671, NULL, NULL, NULL),
(670, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 672, NULL, NULL, NULL),
(671, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 673, NULL, NULL, NULL),
(672, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 674, NULL, NULL, NULL),
(673, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 675, NULL, NULL, NULL),
(674, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 676, NULL, NULL, NULL),
(675, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 677, NULL, NULL, NULL),
(676, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 678, NULL, NULL, NULL),
(677, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 679, NULL, NULL, NULL),
(678, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 680, NULL, NULL, NULL),
(679, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 681, NULL, NULL, NULL),
(680, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 682, NULL, NULL, NULL),
(681, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 683, NULL, NULL, NULL),
(682, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 684, NULL, NULL, NULL),
(683, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 685, NULL, NULL, NULL),
(684, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 686, NULL, NULL, NULL),
(685, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 687, NULL, NULL, NULL),
(686, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 688, NULL, NULL, NULL),
(687, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 689, NULL, NULL, NULL),
(688, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 690, NULL, NULL, NULL),
(689, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 250.00, 0.00, 0.00, 18, 295.00, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 691, NULL, NULL, NULL),
(690, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 692, NULL, NULL, NULL),
(691, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 693, NULL, NULL, NULL),
(692, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 694, NULL, NULL, NULL),
(693, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 695, NULL, NULL, NULL),
(694, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 696, NULL, NULL, NULL),
(695, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 697, NULL, NULL, NULL),
(696, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 698, NULL, NULL, NULL),
(697, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 699, NULL, NULL, NULL),
(698, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 700, NULL, NULL, NULL),
(699, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 701, NULL, NULL, NULL),
(700, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 702, NULL, NULL, NULL),
(701, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 703, NULL, NULL, NULL),
(702, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 704, NULL, NULL, NULL),
(703, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 705, NULL, NULL, NULL),
(704, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 706, NULL, NULL, NULL),
(705, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 707, NULL, NULL, NULL),
(706, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 708, NULL, NULL, NULL),
(707, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 709, NULL, NULL, NULL),
(708, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 710, NULL, NULL, NULL),
(709, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 711, NULL, NULL, NULL),
(710, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 712, NULL, NULL, NULL),
(711, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 713, NULL, NULL, NULL),
(712, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 714, NULL, NULL, NULL),
(713, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 715, NULL, NULL, NULL),
(714, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 716, NULL, NULL, NULL),
(715, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 717, NULL, NULL, NULL),
(716, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 718, NULL, NULL, NULL),
(717, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 719, NULL, NULL, NULL),
(718, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 720, NULL, NULL, NULL),
(719, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 721, NULL, NULL, NULL),
(720, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 722, NULL, NULL, NULL),
(721, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 723, NULL, NULL, NULL),
(722, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 724, NULL, NULL, NULL),
(723, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 725, NULL, NULL, NULL),
(724, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 726, NULL, NULL, NULL),
(725, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 727, NULL, NULL, NULL),
(726, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 728, NULL, NULL, NULL),
(727, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 729, NULL, NULL, NULL),
(728, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 730, NULL, NULL, NULL),
(729, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 12.00, 0.00, 0.00, 18, 14.16, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 731, NULL, NULL, NULL),
(730, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 732, NULL, NULL, NULL),
(731, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 733, NULL, NULL, NULL),
(732, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 734, NULL, NULL, NULL),
(733, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 735, NULL, NULL, NULL),
(734, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 736, NULL, NULL, NULL),
(735, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 737, NULL, NULL, NULL),
(736, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 738, NULL, NULL, NULL),
(737, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 739, NULL, NULL, NULL),
(738, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 740, NULL, NULL, NULL),
(739, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 741, NULL, NULL, NULL),
(740, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 742, NULL, NULL, NULL),
(741, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 743, NULL, NULL, NULL),
(742, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 744, NULL, NULL, NULL),
(743, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 745, NULL, NULL, NULL),
(744, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 746, NULL, NULL, NULL),
(745, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 747, NULL, NULL, NULL),
(746, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 748, NULL, NULL, NULL),
(747, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 749, NULL, NULL, NULL),
(748, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 750, NULL, NULL, NULL),
(749, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 751, NULL, NULL, NULL),
(750, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 752, NULL, NULL, NULL),
(751, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 25.00, 0.00, 0.00, 18, 29.50, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 753, NULL, NULL, NULL),
(752, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 754, NULL, NULL, NULL),
(753, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 755, NULL, NULL, NULL),
(754, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 756, NULL, NULL, NULL),
(755, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 757, NULL, NULL, NULL),
(756, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 758, NULL, NULL, NULL),
(757, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 759, NULL, NULL, NULL),
(758, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 760, NULL, NULL, NULL),
(759, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 761, NULL, NULL, NULL),
(760, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 762, NULL, NULL, NULL),
(761, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 763, NULL, NULL, NULL),
(762, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 764, NULL, NULL, NULL),
(763, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 765, NULL, NULL, NULL),
(764, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 766, NULL, NULL, NULL),
(765, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 767, NULL, NULL, NULL),
(766, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 768, NULL, NULL, NULL),
(767, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 769, NULL, NULL, NULL),
(768, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 770, NULL, NULL, NULL),
(769, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 772, NULL, NULL, NULL),
(770, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 773, NULL, NULL, NULL),
(771, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 774, NULL, NULL, NULL),
(772, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 775, NULL, NULL, NULL),
(773, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 5.00, 0.00, 0.00, 18, 5.90, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 776, NULL, NULL, NULL),
(774, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 10.00, 0.00, 0.00, 18, 11.80, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 777, NULL, NULL, NULL),
(775, 'JUNIO - 2022', '2022-06-01', '2022-07-11', NULL, 18.00, 0.00, 0.00, 18, 21.24, 1, '2022-07-01 00:00:00', '2022-07-01 00:00:00', NULL, 778, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SECTOR`
--

CREATE TABLE `SECTOR` (
  `STR_CODIGO` int NOT NULL,
  `STR_NOMBRE` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `STR_CREATED` datetime NOT NULL,
  `STR_UPDATED` datetime NOT NULL,
  `STR_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `SECTOR`
--

INSERT INTO `SECTOR` (`STR_CODIGO`, `STR_NOMBRE`, `STR_CREATED`, `STR_UPDATED`, `STR_DELETED`) VALUES
(1, 'SECTOR I', '2022-02-10 00:00:00', '2022-03-24 09:39:48', NULL),
(2, 'SECTOR II', '2022-02-23 19:07:06', '2022-03-24 09:40:13', NULL),
(3, 'SECTOR III', '2022-03-19 20:03:23', '2022-03-24 09:40:28', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SECTOR_CALLE`
--

CREATE TABLE `SECTOR_CALLE` (
  `STC_CODIGO` int NOT NULL,
  `STR_CODIGO` int NOT NULL,
  `CAL_CODIGO` int NOT NULL,
  `STC_CREATED` datetime NOT NULL,
  `STC_UPDATED` datetime NOT NULL,
  `STC_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `SECTOR_CALLE`
--

INSERT INTO `SECTOR_CALLE` (`STC_CODIGO`, `STR_CODIGO`, `CAL_CODIGO`, `STC_CREATED`, `STC_UPDATED`, `STC_DELETED`) VALUES
(4, 1, 5, '2022-02-23 19:08:53', '2022-02-23 19:08:53', NULL),
(5, 1, 6, '2022-02-23 19:09:07', '2022-02-23 19:09:07', NULL),
(6, 1, 7, '2022-02-23 19:09:16', '2022-02-23 19:09:16', NULL),
(9, 1, 8, '2022-03-23 22:55:27', '2022-03-23 22:55:27', NULL),
(10, 2, 4, '2022-03-23 23:01:02', '2022-03-23 23:01:02', NULL),
(11, 2, 2, '2022-03-23 23:02:51', '2022-03-23 23:02:51', NULL),
(13, 1, 1, '2022-03-23 23:04:31', '2022-03-23 23:04:31', NULL),
(14, 2, 3, '2022-03-23 23:05:04', '2022-03-23 23:05:04', NULL),
(15, 2, 9, '2022-03-23 23:05:41', '2022-03-23 23:05:41', NULL),
(16, 3, 10, '2022-03-23 23:06:05', '2022-03-23 23:06:05', NULL),
(17, 1, 11, '2022-03-23 23:06:34', '2022-03-23 23:06:34', NULL),
(18, 1, 12, '2022-03-23 23:07:06', '2022-03-23 23:07:06', NULL),
(19, 1, 13, '2022-03-23 23:08:33', '2022-03-23 23:08:33', NULL),
(20, 2, 14, '2022-03-23 23:08:49', '2022-03-23 23:08:49', NULL),
(21, 2, 15, '2022-03-23 23:09:07', '2022-03-23 23:09:07', NULL),
(22, 3, 16, '2022-03-23 23:09:37', '2022-03-23 23:09:37', NULL),
(23, 3, 17, '2022-03-23 23:09:54', '2022-03-23 23:09:54', NULL),
(24, 2, 18, '2022-03-23 23:10:14', '2022-03-23 23:10:14', NULL),
(25, 1, 19, '2022-03-23 23:10:31', '2022-03-23 23:10:31', NULL),
(26, 3, 20, '2022-03-23 23:11:43', '2022-03-23 23:11:43', NULL),
(27, 3, 21, '2022-03-23 23:12:11', '2022-03-23 23:12:11', NULL),
(28, 1, 22, '2022-03-23 23:12:23', '2022-03-23 23:12:23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SERVICIO`
--

CREATE TABLE `SERVICIO` (
  `SRV_CODIGO` int NOT NULL,
  `SRV_NOMBRE` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `SRV_CREATED` datetime NOT NULL,
  `SRV_UPDATED` datetime NOT NULL,
  `SRV_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `SERVICIO`
--

INSERT INTO `SERVICIO` (`SRV_CODIGO`, `SRV_NOMBRE`, `SRV_CREATED`, `SRV_UPDATED`, `SRV_DELETED`) VALUES
(1, 'AGUA POTABLE', '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL),
(2, 'ALCANTARILLADO', '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SERVICIO_ADICIONAL_RBO`
--

CREATE TABLE `SERVICIO_ADICIONAL_RBO` (
  `SAR_CODIGO` int NOT NULL,
  `SAR_DESCRIPCION` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `SAR_COSTO` double(5,2) NOT NULL,
  `SAR_ESTADO` int NOT NULL,
  `SAR_CODIGO_RBO` int DEFAULT NULL,
  `SAR_CREATED` datetime NOT NULL,
  `SAR_UPDATED` datetime NOT NULL,
  `CTO_CODIGO` int NOT NULL,
  `SAR_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SERVICIO_CONTRATO`
--

CREATE TABLE `SERVICIO_CONTRATO` (
  `SRC_CODIGO` int NOT NULL,
  `SRC_CREATED` datetime NOT NULL,
  `SRC_UPDATED` datetime NOT NULL,
  `SRV_CODIGO` int NOT NULL,
  `CTO_CODIGO` int NOT NULL,
  `SRC_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `SERVICIO_CONTRATO`
--

INSERT INTO `SERVICIO_CONTRATO` (`SRC_CODIGO`, `SRC_CREATED`, `SRC_UPDATED`, `SRV_CODIGO`, `CTO_CODIGO`, `SRC_DELETED`) VALUES
(1, '2022-02-23 18:40:34', '2022-02-23 18:40:34', 1, 1, NULL),
(2, '2022-02-23 18:40:34', '2022-02-23 18:40:34', 2, 1, NULL),
(3, '2022-02-23 18:42:37', '2022-02-23 18:42:37', 1, 2, NULL),
(4, '2022-02-23 18:45:07', '2022-02-23 18:45:07', 1, 3, NULL),
(5, '2022-02-23 18:45:07', '2022-02-23 18:45:07', 2, 3, NULL),
(6, '2022-02-23 18:47:53', '2022-02-23 18:47:53', 1, 4, NULL),
(7, '2022-02-23 18:47:53', '2022-02-23 18:47:53', 2, 4, NULL),
(8, '2022-02-23 18:50:08', '2022-02-23 18:50:08', 1, 5, NULL),
(9, '2022-02-23 18:50:08', '2022-02-23 18:50:08', 2, 5, NULL),
(10, '2022-02-23 18:52:13', '2022-02-23 18:52:13', 1, 6, NULL),
(11, '2022-02-23 18:52:13', '2022-02-23 18:52:13', 2, 6, NULL),
(12, '2022-02-23 18:52:41', '2022-02-23 18:52:41', 1, 7, NULL),
(13, '2022-03-23 21:59:14', '2022-03-23 21:59:14', 1, 8, NULL),
(14, '2022-03-23 21:59:14', '2022-03-23 21:59:14', 2, 8, NULL),
(15, '2022-03-24 00:57:05', '2022-03-24 00:57:05', 1, 9, NULL),
(16, '2022-03-24 00:57:05', '2022-03-24 00:57:05', 2, 9, NULL),
(17, '2022-03-24 01:13:49', '2022-03-24 01:13:49', 1, 10, NULL),
(18, '2022-03-24 01:13:49', '2022-03-24 01:13:49', 2, 10, NULL),
(19, '2022-03-24 01:42:08', '2022-03-24 01:42:08', 1, 11, NULL),
(20, '2022-03-24 01:42:08', '2022-03-24 01:42:08', 2, 11, NULL),
(21, '2022-03-24 01:46:46', '2022-03-24 01:46:46', 1, 12, NULL),
(22, '2022-03-24 01:46:46', '2022-03-24 01:46:46', 2, 12, NULL),
(23, '2022-03-28 00:00:00', '2022-03-28 00:00:00', 2, 2, NULL),
(24, '2022-03-29 01:20:16', '2022-03-29 01:20:16', 1, 13, NULL),
(25, '2022-03-29 01:20:16', '2022-03-29 01:20:16', 2, 13, NULL),
(26, '2022-03-29 01:21:52', '2022-03-29 01:21:52', 1, 14, NULL),
(27, '2022-03-29 01:21:52', '2022-03-29 01:21:52', 2, 14, NULL),
(28, '2022-03-29 01:24:52', '2022-03-29 01:24:52', 1, 15, NULL),
(29, '2022-03-29 01:24:52', '2022-03-29 01:24:52', 2, 15, NULL),
(30, '2022-03-29 01:26:15', '2022-03-29 01:26:15', 1, 16, NULL),
(31, '2022-03-29 01:26:15', '2022-03-29 01:26:15', 2, 16, NULL),
(32, '2022-03-29 01:27:50', '2022-03-29 01:27:50', 1, 17, NULL),
(33, '2022-03-29 01:27:50', '2022-03-29 01:27:50', 2, 17, NULL),
(34, '2022-03-29 01:29:19', '2022-03-29 01:29:19', 1, 18, NULL),
(35, '2022-03-29 01:29:19', '2022-03-29 01:29:19', 2, 18, NULL),
(36, '2022-03-29 01:30:59', '2022-03-29 01:30:59', 1, 19, NULL),
(37, '2022-03-29 01:30:59', '2022-03-29 01:30:59', 2, 19, NULL),
(38, '2022-03-29 01:32:29', '2022-03-29 01:32:29', 1, 20, NULL),
(39, '2022-03-29 01:32:29', '2022-03-29 01:32:29', 2, 20, NULL),
(40, '2022-03-29 01:36:32', '2022-03-29 01:36:32', 1, 21, NULL),
(41, '2022-03-29 01:36:32', '2022-03-29 01:36:32', 2, 21, NULL),
(42, '2022-03-29 01:37:56', '2022-03-29 01:37:56', 1, 22, NULL),
(43, '2022-03-29 01:37:56', '2022-03-29 01:37:56', 2, 22, NULL),
(44, '2022-03-29 01:39:31', '2022-03-29 01:39:31', 1, 23, NULL),
(45, '2022-03-29 01:39:31', '2022-03-29 01:39:31', 2, 23, NULL),
(46, '2022-03-29 01:40:37', '2022-03-29 01:40:37', 1, 24, NULL),
(47, '2022-03-29 01:40:37', '2022-03-29 01:40:37', 2, 24, NULL),
(48, '2022-03-29 01:47:07', '2022-03-29 01:47:07', 1, 25, NULL),
(49, '2022-03-29 01:47:07', '2022-03-29 01:47:07', 2, 25, NULL),
(50, '2022-03-29 01:49:08', '2022-03-29 01:49:08', 1, 26, NULL),
(51, '2022-03-29 01:49:08', '2022-03-29 01:49:08', 2, 26, NULL),
(52, '2022-03-29 01:51:33', '2022-03-29 01:51:33', 1, 27, NULL),
(53, '2022-03-29 01:51:33', '2022-03-29 01:51:33', 2, 27, NULL),
(54, '2022-03-29 01:53:01', '2022-03-29 01:53:01', 1, 28, NULL),
(55, '2022-03-29 01:53:01', '2022-03-29 01:53:01', 2, 28, NULL),
(56, '2022-03-29 01:57:01', '2022-03-29 01:57:01', 1, 29, NULL),
(57, '2022-03-29 01:57:01', '2022-03-29 01:57:01', 2, 29, NULL),
(58, '2022-03-29 02:02:01', '2022-03-29 02:02:01', 1, 30, NULL),
(59, '2022-03-29 02:02:01', '2022-03-29 02:02:01', 2, 30, NULL),
(60, '2022-03-29 02:03:31', '2022-03-29 02:03:31', 1, 31, NULL),
(61, '2022-03-29 02:03:31', '2022-03-29 02:03:31', 2, 31, NULL),
(62, '2022-03-29 02:05:31', '2022-03-29 02:05:31', 1, 32, NULL),
(63, '2022-03-29 02:05:31', '2022-03-29 02:05:31', 2, 32, NULL),
(64, '2022-03-29 02:07:52', '2022-03-29 02:07:52', 1, 33, NULL),
(65, '2022-03-29 02:07:52', '2022-03-29 02:07:52', 2, 33, NULL),
(66, '2022-03-29 02:12:17', '2022-03-29 02:12:17', 1, 34, NULL),
(67, '2022-03-29 02:12:17', '2022-03-29 02:12:17', 2, 34, NULL),
(68, '2022-03-29 02:13:17', '2022-03-29 02:13:17', 1, 35, NULL),
(69, '2022-03-29 02:13:17', '2022-03-29 02:13:17', 2, 35, NULL),
(70, '2022-03-29 02:14:15', '2022-03-29 02:14:15', 1, 36, NULL),
(71, '2022-03-29 02:14:15', '2022-03-29 02:14:15', 2, 36, NULL),
(72, '2022-03-29 02:17:13', '2022-03-29 02:17:13', 1, 37, NULL),
(73, '2022-03-29 02:17:13', '2022-03-29 02:17:13', 2, 37, NULL),
(74, '2022-03-29 02:20:10', '2022-03-29 02:20:10', 1, 38, NULL),
(75, '2022-03-29 02:20:10', '2022-03-29 02:20:10', 2, 38, NULL),
(76, '2022-03-29 02:22:24', '2022-03-29 02:22:24', 1, 39, NULL),
(77, '2022-03-29 02:22:24', '2022-03-29 02:22:24', 2, 39, NULL),
(78, '2022-03-29 02:23:10', '2022-03-29 02:23:10', 1, 40, NULL),
(79, '2022-03-29 02:23:10', '2022-03-29 02:23:10', 2, 40, NULL),
(80, '2022-03-29 02:24:33', '2022-03-29 02:24:33', 1, 41, NULL),
(81, '2022-03-29 02:24:33', '2022-03-29 02:24:33', 2, 41, NULL),
(82, '2022-03-29 02:25:58', '2022-03-29 02:25:58', 1, 42, NULL),
(83, '2022-03-29 02:25:58', '2022-03-29 02:25:58', 2, 42, NULL),
(84, '2022-03-29 02:26:44', '2022-03-29 02:26:44', 1, 43, NULL),
(85, '2022-03-29 02:26:44', '2022-03-29 02:26:44', 2, 43, NULL),
(86, '2022-03-29 02:28:54', '2022-03-29 02:28:54', 1, 44, NULL),
(87, '2022-03-29 02:28:54', '2022-03-29 02:28:54', 2, 44, NULL),
(88, '2022-03-29 02:30:04', '2022-03-29 02:30:04', 1, 45, NULL),
(89, '2022-03-29 02:30:04', '2022-03-29 02:30:04', 2, 45, NULL),
(90, '2022-03-29 03:04:34', '2022-03-29 03:04:34', 1, 46, NULL),
(91, '2022-03-29 03:04:34', '2022-03-29 03:04:34', 2, 46, NULL),
(92, '2022-03-29 03:05:25', '2022-03-29 03:05:25', 1, 47, NULL),
(93, '2022-03-29 03:05:25', '2022-03-29 03:05:25', 2, 47, NULL),
(94, '2022-03-29 03:06:59', '2022-03-29 03:06:59', 1, 48, NULL),
(95, '2022-03-29 03:06:59', '2022-03-29 03:06:59', 2, 48, NULL),
(96, '2022-03-29 03:08:01', '2022-03-29 03:08:01', 1, 49, NULL),
(97, '2022-03-29 03:08:01', '2022-03-29 03:08:01', 2, 49, NULL),
(98, '2022-03-29 03:09:16', '2022-03-29 03:09:16', 1, 50, NULL),
(99, '2022-03-29 03:09:16', '2022-03-29 03:09:16', 2, 50, NULL),
(100, '2022-03-29 03:10:45', '2022-03-29 03:10:45', 1, 51, NULL),
(101, '2022-03-29 03:10:45', '2022-03-29 03:10:45', 2, 51, NULL),
(102, '2022-03-29 03:12:15', '2022-03-29 03:12:15', 1, 52, NULL),
(103, '2022-03-29 03:12:15', '2022-03-29 03:12:15', 2, 52, NULL),
(104, '2022-03-29 03:13:03', '2022-03-29 03:13:03', 1, 53, NULL),
(105, '2022-03-29 03:13:03', '2022-03-29 03:13:03', 2, 53, NULL),
(106, '2022-03-29 03:14:06', '2022-03-29 03:14:06', 1, 54, NULL),
(107, '2022-03-29 03:14:06', '2022-03-29 03:14:06', 2, 54, NULL),
(108, '2022-03-29 03:15:36', '2022-03-29 03:15:36', 1, 55, NULL),
(109, '2022-03-29 03:15:36', '2022-03-29 03:15:36', 2, 55, NULL),
(110, '2022-03-29 03:16:27', '2022-03-29 03:16:27', 1, 56, NULL),
(111, '2022-03-29 03:16:27', '2022-03-29 03:16:27', 2, 56, NULL),
(112, '2022-03-29 03:17:55', '2022-03-29 03:17:55', 1, 57, NULL),
(113, '2022-03-29 03:17:55', '2022-03-29 03:17:55', 2, 57, NULL),
(114, '2022-03-29 03:19:04', '2022-03-29 03:19:04', 1, 58, NULL),
(115, '2022-03-29 03:19:04', '2022-03-29 03:19:04', 2, 58, NULL),
(116, '2022-03-29 03:23:09', '2022-03-29 03:23:09', 1, 59, NULL),
(117, '2022-03-29 03:23:09', '2022-03-29 03:23:09', 2, 59, NULL),
(118, '2022-03-29 03:25:15', '2022-03-29 03:25:15', 1, 60, NULL),
(119, '2022-03-29 03:25:15', '2022-03-29 03:25:15', 2, 60, NULL),
(120, '2022-03-29 03:26:38', '2022-03-29 03:26:38', 1, 61, NULL),
(121, '2022-03-29 03:26:38', '2022-03-29 03:26:38', 2, 61, NULL),
(122, '2022-03-29 03:27:52', '2022-03-29 03:27:52', 1, 62, NULL),
(123, '2022-03-29 03:27:52', '2022-03-29 03:27:52', 2, 62, NULL),
(124, '2022-03-29 03:29:30', '2022-03-29 03:29:30', 1, 63, NULL),
(125, '2022-03-29 03:29:30', '2022-03-29 03:29:30', 2, 63, NULL),
(126, '2022-03-29 03:30:41', '2022-03-29 03:30:41', 1, 64, NULL),
(127, '2022-03-29 03:30:41', '2022-03-29 03:30:41', 2, 64, NULL),
(128, '2022-03-29 03:32:13', '2022-03-29 03:32:13', 1, 65, NULL),
(129, '2022-03-29 03:32:13', '2022-03-29 03:32:13', 2, 65, NULL),
(130, '2022-03-29 03:33:51', '2022-03-29 03:33:51', 1, 66, NULL),
(131, '2022-03-29 03:33:51', '2022-03-29 03:33:51', 2, 66, NULL),
(132, '2022-03-29 03:39:00', '2022-03-29 03:39:00', 1, 67, NULL),
(133, '2022-03-29 03:39:00', '2022-03-29 03:39:00', 2, 67, NULL),
(134, '2022-03-29 03:39:56', '2022-03-29 03:39:56', 1, 68, NULL),
(135, '2022-03-29 03:39:56', '2022-03-29 03:39:56', 2, 68, NULL),
(136, '2022-03-29 03:44:55', '2022-03-29 03:44:55', 1, 69, NULL),
(137, '2022-03-29 03:44:55', '2022-03-29 03:44:55', 2, 69, NULL),
(138, '2022-03-29 03:46:26', '2022-03-29 03:46:26', 1, 70, NULL),
(139, '2022-03-29 03:46:26', '2022-03-29 03:46:26', 2, 70, NULL),
(140, '2022-03-29 03:47:28', '2022-03-29 03:47:28', 1, 71, NULL),
(141, '2022-03-29 03:47:28', '2022-03-29 03:47:28', 2, 71, NULL),
(142, '2022-03-29 03:48:32', '2022-03-29 03:48:32', 1, 72, NULL),
(143, '2022-03-29 03:48:32', '2022-03-29 03:48:32', 2, 72, NULL),
(144, '2022-03-29 03:49:35', '2022-03-29 03:49:35', 1, 73, NULL),
(145, '2022-03-29 03:49:35', '2022-03-29 03:49:35', 2, 73, NULL),
(146, '2022-03-29 03:50:43', '2022-03-29 03:50:43', 1, 74, NULL),
(147, '2022-03-29 03:50:43', '2022-03-29 03:50:43', 2, 74, NULL),
(148, '2022-03-29 03:52:24', '2022-03-29 03:52:24', 1, 75, NULL),
(149, '2022-03-29 03:52:24', '2022-03-29 03:52:24', 2, 75, NULL),
(150, '2022-03-29 03:53:52', '2022-03-29 03:53:52', 1, 76, NULL),
(151, '2022-03-29 03:53:52', '2022-03-29 03:53:52', 2, 76, NULL),
(152, '2022-03-29 03:55:47', '2022-03-29 03:55:47', 1, 77, NULL),
(153, '2022-03-29 03:55:47', '2022-03-29 03:55:47', 2, 77, NULL),
(154, '2022-03-29 03:56:41', '2022-03-29 03:56:41', 1, 78, NULL),
(155, '2022-03-29 03:56:41', '2022-03-29 03:56:41', 2, 78, NULL),
(156, '2022-03-29 03:59:52', '2022-03-29 03:59:52', 1, 79, NULL),
(157, '2022-03-29 03:59:52', '2022-03-29 03:59:52', 2, 79, NULL),
(158, '2022-03-29 04:01:08', '2022-03-29 04:01:08', 1, 80, NULL),
(159, '2022-03-29 04:01:08', '2022-03-29 04:01:08', 2, 80, NULL),
(160, '2022-03-29 04:02:41', '2022-03-29 04:02:41', 1, 81, NULL),
(161, '2022-03-29 04:02:41', '2022-03-29 04:02:41', 2, 81, NULL),
(162, '2022-03-29 04:03:50', '2022-03-29 04:03:50', 1, 82, NULL),
(163, '2022-03-29 04:03:50', '2022-03-29 04:03:50', 2, 82, NULL),
(164, '2022-03-29 04:04:55', '2022-03-29 04:04:55', 1, 83, NULL),
(165, '2022-03-29 04:04:55', '2022-03-29 04:04:55', 2, 83, NULL),
(166, '2022-03-29 04:05:49', '2022-03-29 04:05:49', 1, 84, NULL),
(167, '2022-03-29 04:05:49', '2022-03-29 04:05:49', 2, 84, NULL),
(168, '2022-03-29 04:06:49', '2022-03-29 04:06:49', 1, 85, NULL),
(169, '2022-03-29 04:06:49', '2022-03-29 04:06:49', 2, 85, NULL),
(170, '2022-03-29 04:07:56', '2022-03-29 04:07:56', 1, 86, NULL),
(171, '2022-03-29 04:07:56', '2022-03-29 04:07:56', 2, 86, NULL),
(172, '2022-03-29 04:08:56', '2022-03-29 04:08:56', 1, 87, NULL),
(173, '2022-03-29 04:08:56', '2022-03-29 04:08:56', 2, 87, NULL),
(174, '2022-03-29 04:10:27', '2022-03-29 04:10:27', 1, 88, NULL),
(175, '2022-03-29 04:10:27', '2022-03-29 04:10:27', 2, 88, NULL),
(176, '2022-03-29 04:11:17', '2022-03-29 04:11:17', 1, 89, NULL),
(177, '2022-03-29 04:11:17', '2022-03-29 04:11:17', 2, 89, NULL),
(178, '2022-03-29 04:12:30', '2022-03-29 04:12:30', 1, 90, NULL),
(179, '2022-03-29 04:12:30', '2022-03-29 04:12:30', 2, 90, NULL),
(180, '2022-03-29 04:13:35', '2022-03-29 04:13:35', 1, 91, NULL),
(181, '2022-03-29 04:13:35', '2022-03-29 04:13:35', 2, 91, NULL),
(182, '2022-03-29 04:17:02', '2022-03-29 04:17:02', 1, 92, NULL),
(183, '2022-03-29 04:17:02', '2022-03-29 04:17:02', 2, 92, NULL),
(184, '2022-03-29 04:17:56', '2022-03-29 04:17:56', 1, 93, NULL),
(185, '2022-03-29 04:17:56', '2022-03-29 04:17:56', 2, 93, NULL),
(186, '2022-03-29 04:19:27', '2022-03-29 04:19:27', 1, 94, NULL),
(187, '2022-03-29 04:19:27', '2022-03-29 04:19:27', 2, 94, NULL),
(188, '2022-03-29 04:20:05', '2022-03-29 04:20:05', 1, 95, NULL),
(189, '2022-03-29 04:20:05', '2022-03-29 04:20:05', 2, 95, NULL),
(190, '2022-03-29 04:20:47', '2022-03-29 04:20:47', 1, 96, NULL),
(191, '2022-03-29 04:20:47', '2022-03-29 04:20:47', 2, 96, NULL),
(192, '2022-03-29 04:21:40', '2022-03-29 04:21:40', 1, 97, NULL),
(193, '2022-03-29 04:21:40', '2022-03-29 04:21:40', 2, 97, NULL),
(194, '2022-03-29 04:23:20', '2022-03-29 04:23:20', 1, 98, NULL),
(195, '2022-03-29 04:23:20', '2022-03-29 04:23:20', 2, 98, NULL),
(196, '2022-03-29 04:25:35', '2022-03-29 04:25:35', 1, 99, NULL),
(197, '2022-03-29 04:25:35', '2022-03-29 04:25:35', 2, 99, NULL),
(198, '2022-03-29 04:27:35', '2022-03-29 04:27:35', 1, 100, NULL),
(199, '2022-03-29 04:27:35', '2022-03-29 04:27:35', 2, 100, NULL),
(200, '2022-03-29 22:55:31', '2022-03-29 22:55:31', 1, 101, NULL),
(201, '2022-03-29 22:55:31', '2022-03-29 22:55:31', 2, 101, NULL),
(202, '2022-03-29 22:59:30', '2022-03-29 22:59:30', 1, 102, NULL),
(203, '2022-03-29 22:59:30', '2022-03-29 22:59:30', 2, 102, NULL),
(204, '2022-03-29 23:03:44', '2022-03-29 23:03:44', 1, 103, NULL),
(205, '2022-03-29 23:03:44', '2022-03-29 23:03:44', 2, 103, NULL),
(206, '2022-03-29 23:06:57', '2022-03-29 23:06:57', 1, 104, NULL),
(207, '2022-03-29 23:06:57', '2022-03-29 23:06:57', 2, 104, NULL),
(208, '2022-03-29 23:10:31', '2022-03-29 23:10:31', 1, 105, NULL),
(209, '2022-03-29 23:10:31', '2022-03-29 23:10:31', 2, 105, NULL),
(210, '2022-03-29 23:13:38', '2022-03-29 23:13:38', 2, 106, NULL),
(211, '2022-03-29 23:16:27', '2022-03-29 23:16:27', 1, 107, NULL),
(212, '2022-03-29 23:16:27', '2022-03-29 23:16:27', 2, 107, NULL),
(213, '2022-03-29 23:25:22', '2022-03-29 23:25:22', 2, 108, NULL),
(214, '2022-03-29 23:29:29', '2022-03-29 23:29:29', 1, 109, NULL),
(215, '2022-03-29 23:29:29', '2022-03-29 23:29:29', 2, 109, NULL),
(216, '2022-03-29 23:32:04', '2022-03-29 23:32:04', 1, 110, NULL),
(217, '2022-03-29 23:32:04', '2022-03-29 23:32:04', 2, 110, NULL),
(218, '2022-03-29 23:33:16', '2022-03-29 23:33:16', 1, 111, NULL),
(219, '2022-03-29 23:34:00', '2022-03-29 23:34:00', 1, 112, NULL),
(220, '2022-03-29 23:39:23', '2022-03-29 23:39:23', 1, 113, NULL),
(221, '2022-03-29 23:39:23', '2022-03-29 23:39:23', 2, 113, NULL),
(222, '2022-03-29 23:43:53', '2022-03-29 23:43:53', 1, 114, NULL),
(223, '2022-03-29 23:43:53', '2022-03-29 23:43:53', 2, 114, NULL),
(224, '2022-03-29 23:48:02', '2022-03-29 23:48:02', 2, 115, NULL),
(225, '2022-03-29 23:53:26', '2022-03-29 23:53:26', 1, 116, NULL),
(226, '2022-03-29 23:58:48', '2022-03-29 23:58:48', 1, 117, NULL),
(227, '2022-03-29 23:58:48', '2022-03-29 23:58:48', 2, 117, NULL),
(228, '2022-03-30 00:02:43', '2022-03-30 00:02:43', 2, 118, NULL),
(229, '2022-03-30 00:12:39', '2022-03-30 00:12:39', 1, 119, NULL),
(230, '2022-03-30 00:12:39', '2022-03-30 00:12:39', 2, 119, NULL),
(231, '2022-03-30 00:17:56', '2022-03-30 00:17:56', 1, 120, NULL),
(232, '2022-03-30 00:17:56', '2022-03-30 00:17:56', 2, 120, NULL),
(233, '2022-03-30 00:19:24', '2022-03-30 00:19:24', 1, 121, NULL),
(234, '2022-03-30 00:19:24', '2022-03-30 00:19:24', 2, 121, NULL),
(235, '2022-03-30 00:23:29', '2022-03-30 00:23:29', 1, 122, NULL),
(236, '2022-03-30 00:23:29', '2022-03-30 00:23:29', 2, 122, NULL),
(237, '2022-03-30 00:27:45', '2022-03-30 00:27:45', 1, 123, NULL),
(238, '2022-03-30 00:27:45', '2022-03-30 00:27:45', 2, 123, NULL),
(239, '2022-03-30 00:31:35', '2022-03-30 00:31:35', 1, 124, NULL),
(240, '2022-03-30 00:31:35', '2022-03-30 00:31:35', 2, 124, NULL),
(241, '2022-03-30 00:32:55', '2022-03-30 00:32:55', 2, 125, NULL),
(242, '2022-03-30 00:40:14', '2022-03-30 00:40:14', 1, 126, NULL),
(243, '2022-03-30 00:40:14', '2022-03-30 00:40:14', 2, 126, NULL),
(244, '2022-03-30 00:43:26', '2022-03-30 00:43:26', 1, 127, NULL),
(245, '2022-03-30 00:43:26', '2022-03-30 00:43:26', 2, 127, NULL),
(246, '2022-03-30 00:49:58', '2022-03-30 00:49:58', 1, 128, NULL),
(247, '2022-03-30 00:49:58', '2022-03-30 00:49:58', 2, 128, NULL),
(248, '2022-03-30 00:52:56', '2022-03-30 00:52:56', 1, 129, NULL),
(249, '2022-03-30 00:55:22', '2022-03-30 00:55:22', 1, 130, NULL),
(250, '2022-03-30 00:55:22', '2022-03-30 00:55:22', 2, 130, NULL),
(251, '2022-03-30 00:58:19', '2022-03-30 00:58:19', 1, 131, NULL),
(252, '2022-03-30 00:58:19', '2022-03-30 00:58:19', 2, 131, NULL),
(253, '2022-03-30 00:59:21', '2022-03-30 00:59:21', 1, 132, NULL),
(254, '2022-03-30 01:04:38', '2022-03-30 01:04:38', 1, 133, NULL),
(255, '2022-03-30 01:04:38', '2022-03-30 01:04:38', 2, 133, NULL),
(256, '2022-03-30 01:15:11', '2022-03-30 01:15:11', 1, 134, NULL),
(257, '2022-03-30 01:15:11', '2022-03-30 01:15:11', 2, 134, NULL),
(258, '2022-03-30 01:17:30', '2022-03-30 01:17:30', 1, 135, NULL),
(259, '2022-03-30 01:17:30', '2022-03-30 01:17:30', 2, 135, NULL),
(260, '2022-03-30 01:21:28', '2022-03-30 01:21:28', 1, 136, NULL),
(261, '2022-03-30 01:21:28', '2022-03-30 01:21:28', 2, 136, NULL),
(262, '2022-03-30 01:28:12', '2022-03-30 01:28:12', 1, 137, NULL),
(263, '2022-03-30 01:28:12', '2022-03-30 01:28:12', 2, 137, NULL),
(264, '2022-03-30 01:32:07', '2022-03-30 01:32:07', 1, 138, NULL),
(265, '2022-03-30 01:32:07', '2022-03-30 01:32:07', 2, 138, NULL),
(266, '2022-03-30 01:38:32', '2022-03-30 01:38:32', 1, 139, NULL),
(267, '2022-03-30 01:42:05', '2022-03-30 01:42:05', 1, 140, NULL),
(268, '2022-03-30 01:42:05', '2022-03-30 01:42:05', 2, 140, NULL),
(269, '2022-03-30 01:45:44', '2022-03-30 01:45:44', 1, 141, NULL),
(270, '2022-03-30 01:45:44', '2022-03-30 01:45:44', 2, 141, NULL),
(271, '2022-03-30 01:48:36', '2022-03-30 01:48:36', 1, 142, NULL),
(272, '2022-03-30 01:48:36', '2022-03-30 01:48:36', 2, 142, NULL),
(273, '2022-03-30 01:57:37', '2022-03-30 01:57:37', 1, 143, NULL),
(274, '2022-03-30 01:57:37', '2022-03-30 01:57:37', 2, 143, NULL),
(275, '2022-03-30 02:01:00', '2022-03-30 02:01:00', 1, 144, NULL),
(276, '2022-03-30 02:01:00', '2022-03-30 02:01:00', 2, 144, NULL),
(277, '2022-03-30 02:06:11', '2022-03-30 02:06:11', 1, 145, NULL),
(278, '2022-03-30 02:06:11', '2022-03-30 02:06:11', 2, 145, NULL),
(279, '2022-03-30 02:07:29', '2022-03-30 02:07:29', 1, 146, NULL),
(280, '2022-03-30 02:11:13', '2022-03-30 02:11:13', 1, 147, NULL),
(281, '2022-03-30 02:11:13', '2022-03-30 02:11:13', 2, 147, NULL),
(282, '2022-03-30 02:20:43', '2022-03-30 02:20:43', 1, 148, NULL),
(283, '2022-03-30 02:20:43', '2022-03-30 02:20:43', 2, 148, NULL),
(284, '2022-03-30 02:23:24', '2022-03-30 02:23:24', 1, 149, NULL),
(285, '2022-03-30 02:23:24', '2022-03-30 02:23:24', 2, 149, NULL),
(286, '2022-03-30 02:27:06', '2022-03-30 02:27:06', 1, 150, NULL),
(287, '2022-03-30 02:27:06', '2022-03-30 02:27:06', 2, 150, NULL),
(288, '2022-03-30 02:30:58', '2022-03-30 02:30:58', 1, 151, NULL),
(289, '2022-03-30 02:30:58', '2022-03-30 02:30:58', 2, 151, NULL),
(290, '2022-03-30 02:34:03', '2022-03-30 02:34:03', 1, 152, NULL),
(291, '2022-03-30 02:34:03', '2022-03-30 02:34:03', 2, 152, NULL),
(292, '2022-03-30 02:36:58', '2022-03-30 02:36:58', 1, 153, NULL),
(293, '2022-03-30 02:36:58', '2022-03-30 02:36:58', 2, 153, NULL),
(294, '2022-03-30 02:40:09', '2022-03-30 02:40:09', 1, 154, NULL),
(295, '2022-03-30 02:40:09', '2022-03-30 02:40:09', 2, 154, NULL),
(296, '2022-03-30 02:42:59', '2022-03-30 02:42:59', 1, 155, NULL),
(297, '2022-03-30 02:42:59', '2022-03-30 02:42:59', 2, 155, NULL),
(298, '2022-03-30 02:45:33', '2022-03-30 02:45:33', 1, 156, NULL),
(299, '2022-03-30 02:45:33', '2022-03-30 02:45:33', 2, 156, NULL),
(300, '2022-03-30 02:48:17', '2022-03-30 02:48:17', 1, 157, NULL),
(301, '2022-03-30 02:48:17', '2022-03-30 02:48:17', 2, 157, NULL),
(302, '2022-03-30 02:51:03', '2022-03-30 02:51:03', 1, 158, NULL),
(303, '2022-03-30 02:51:03', '2022-03-30 02:51:03', 2, 158, NULL),
(304, '2022-03-30 02:53:49', '2022-03-30 02:53:49', 1, 159, NULL),
(305, '2022-03-30 02:53:49', '2022-03-30 02:53:49', 2, 159, NULL),
(306, '2022-03-30 02:57:20', '2022-03-30 02:57:20', 1, 160, NULL),
(307, '2022-03-30 02:57:20', '2022-03-30 02:57:20', 2, 160, NULL),
(308, '2022-03-30 02:59:38', '2022-03-30 02:59:38', 1, 161, NULL),
(309, '2022-03-30 02:59:38', '2022-03-30 02:59:38', 2, 161, NULL),
(310, '2022-03-30 03:02:08', '2022-03-30 03:02:08', 1, 162, NULL),
(311, '2022-03-30 03:02:08', '2022-03-30 03:02:08', 2, 162, NULL),
(312, '2022-03-30 03:07:10', '2022-03-30 03:07:10', 1, 163, NULL),
(313, '2022-03-30 03:07:10', '2022-03-30 03:07:10', 2, 163, NULL),
(314, '2022-03-30 03:09:22', '2022-03-30 03:09:22', 1, 164, NULL),
(315, '2022-03-30 03:09:22', '2022-03-30 03:09:22', 2, 164, NULL),
(316, '2022-03-30 03:10:11', '2022-03-30 03:10:11', 1, 165, NULL),
(317, '2022-03-30 03:10:11', '2022-03-30 03:10:11', 2, 165, NULL),
(318, '2022-03-30 03:15:24', '2022-03-30 03:15:24', 1, 166, NULL),
(319, '2022-03-30 03:15:24', '2022-03-30 03:15:24', 2, 166, NULL),
(320, '2022-03-30 10:09:53', '2022-03-30 10:09:53', 1, 167, NULL),
(321, '2022-03-30 10:09:53', '2022-03-30 10:09:53', 2, 167, NULL),
(322, '2022-03-30 10:17:48', '2022-03-30 10:17:48', 1, 168, NULL),
(323, '2022-03-30 10:17:48', '2022-03-30 10:17:48', 2, 168, NULL),
(324, '2022-03-30 10:27:01', '2022-03-30 10:27:01', 1, 169, NULL),
(325, '2022-03-30 10:27:01', '2022-03-30 10:27:01', 2, 169, NULL),
(326, '2022-03-30 10:31:26', '2022-03-30 10:31:26', 1, 170, NULL),
(327, '2022-03-30 10:31:26', '2022-03-30 10:31:26', 2, 170, NULL),
(328, '2022-03-30 10:36:40', '2022-03-30 10:36:40', 1, 171, NULL),
(329, '2022-03-30 10:36:40', '2022-03-30 10:36:40', 2, 171, NULL),
(330, '2022-03-30 10:43:41', '2022-03-30 10:43:41', 1, 172, NULL),
(331, '2022-03-30 10:56:10', '2022-03-30 10:56:10', 1, 173, NULL),
(332, '2022-03-30 10:56:10', '2022-03-30 10:56:10', 2, 173, NULL),
(333, '2022-03-30 11:00:02', '2022-03-30 11:00:02', 1, 174, NULL),
(334, '2022-03-30 11:00:02', '2022-03-30 11:00:02', 2, 174, NULL),
(335, '2022-03-30 11:04:17', '2022-03-30 11:04:17', 1, 175, NULL),
(336, '2022-03-30 11:04:17', '2022-03-30 11:04:17', 2, 175, NULL),
(337, '2022-03-30 11:13:28', '2022-03-30 11:13:28', 1, 176, NULL),
(338, '2022-03-30 11:13:28', '2022-03-30 11:13:28', 2, 176, NULL),
(339, '2022-03-30 11:16:26', '2022-03-30 11:16:26', 1, 177, NULL),
(340, '2022-03-30 11:16:26', '2022-03-30 11:16:26', 2, 177, NULL),
(341, '2022-03-30 11:21:18', '2022-03-30 11:21:18', 1, 178, NULL),
(342, '2022-03-30 11:21:18', '2022-03-30 11:21:18', 2, 178, NULL),
(343, '2022-03-30 11:28:19', '2022-03-30 11:28:19', 1, 179, NULL),
(344, '2022-03-30 11:28:19', '2022-03-30 11:28:19', 2, 179, NULL),
(345, '2022-03-30 11:31:37', '2022-03-30 11:31:37', 1, 180, NULL),
(346, '2022-03-30 11:31:37', '2022-03-30 11:31:37', 2, 180, NULL),
(347, '2022-03-30 11:36:02', '2022-03-30 11:36:02', 1, 181, NULL),
(348, '2022-03-30 11:36:02', '2022-03-30 11:36:02', 2, 181, NULL),
(349, '2022-03-30 11:42:51', '2022-03-30 11:42:51', 1, 182, NULL),
(350, '2022-03-30 11:42:51', '2022-03-30 11:42:51', 2, 182, NULL),
(351, '2022-03-30 11:47:12', '2022-03-30 11:47:12', 1, 183, NULL),
(352, '2022-03-30 11:47:12', '2022-03-30 11:47:12', 2, 183, NULL),
(353, '2022-03-30 11:50:54', '2022-03-30 11:50:54', 1, 184, NULL),
(354, '2022-03-30 11:50:54', '2022-03-30 11:50:54', 2, 184, NULL),
(355, '2022-03-30 11:56:41', '2022-03-30 11:56:41', 1, 185, NULL),
(356, '2022-03-30 11:56:41', '2022-03-30 11:56:41', 2, 185, NULL),
(357, '2022-03-30 12:02:50', '2022-03-30 12:02:50', 1, 186, NULL),
(358, '2022-03-30 12:02:50', '2022-03-30 12:02:50', 2, 186, NULL),
(359, '2022-03-30 12:08:37', '2022-03-30 12:08:37', 1, 187, NULL),
(360, '2022-03-30 12:08:37', '2022-03-30 12:08:37', 2, 187, NULL),
(361, '2022-03-30 12:13:43', '2022-03-30 12:13:43', 1, 188, NULL),
(362, '2022-03-30 12:13:43', '2022-03-30 12:13:43', 2, 188, NULL),
(363, '2022-03-30 12:17:55', '2022-03-30 12:17:55', 1, 189, NULL),
(364, '2022-03-30 12:17:55', '2022-03-30 12:17:55', 2, 189, NULL),
(365, '2022-03-30 12:22:11', '2022-03-30 12:22:11', 1, 190, NULL),
(366, '2022-03-30 12:22:11', '2022-03-30 12:22:11', 2, 190, NULL),
(367, '2022-03-30 12:27:49', '2022-03-30 12:27:49', 1, 191, NULL),
(368, '2022-03-30 12:27:49', '2022-03-30 12:27:49', 2, 191, NULL),
(369, '2022-03-30 12:34:21', '2022-03-30 12:34:21', 1, 192, NULL),
(370, '2022-03-30 12:34:21', '2022-03-30 12:34:21', 2, 192, NULL),
(371, '2022-03-30 12:39:08', '2022-03-30 12:39:08', 1, 193, NULL),
(372, '2022-03-30 12:39:08', '2022-03-30 12:39:08', 2, 193, NULL),
(373, '2022-03-30 12:44:13', '2022-03-30 12:44:13', 1, 194, NULL),
(374, '2022-03-30 12:44:13', '2022-03-30 12:44:13', 2, 194, NULL),
(375, '2022-03-30 12:48:18', '2022-03-30 12:48:18', 1, 195, NULL),
(376, '2022-03-30 12:48:18', '2022-03-30 12:48:18', 2, 195, NULL),
(377, '2022-03-30 12:51:23', '2022-03-30 12:51:23', 1, 196, NULL),
(378, '2022-03-30 12:51:23', '2022-03-30 12:51:23', 2, 196, NULL),
(379, '2022-03-30 12:56:56', '2022-03-30 12:56:56', 1, 197, NULL),
(380, '2022-03-30 12:56:56', '2022-03-30 12:56:56', 2, 197, NULL),
(381, '2022-03-30 13:34:03', '2022-03-30 13:34:03', 1, 198, NULL),
(382, '2022-03-30 13:34:03', '2022-03-30 13:34:03', 2, 198, NULL),
(383, '2022-03-30 13:38:22', '2022-03-30 13:38:22', 1, 199, NULL),
(384, '2022-03-30 13:38:22', '2022-03-30 13:38:22', 2, 199, NULL),
(385, '2022-03-30 13:42:50', '2022-03-30 13:42:50', 1, 200, NULL),
(386, '2022-03-30 13:42:50', '2022-03-30 13:42:50', 2, 200, NULL),
(387, '2022-03-30 13:49:24', '2022-03-30 13:49:24', 1, 201, NULL),
(388, '2022-03-30 13:49:24', '2022-03-30 13:49:24', 2, 201, NULL),
(389, '2022-03-30 13:54:18', '2022-03-30 13:54:18', 1, 202, NULL),
(390, '2022-03-30 13:54:18', '2022-03-30 13:54:18', 2, 202, NULL),
(391, '2022-03-30 14:01:30', '2022-03-30 14:01:30', 1, 203, NULL),
(392, '2022-03-30 14:01:30', '2022-03-30 14:01:30', 2, 203, NULL),
(393, '2022-03-30 14:04:26', '2022-03-30 14:04:26', 1, 204, NULL),
(394, '2022-03-30 14:04:26', '2022-03-30 14:04:26', 2, 204, NULL),
(395, '2022-03-30 14:09:31', '2022-03-30 14:09:31', 1, 205, NULL),
(396, '2022-03-30 14:09:31', '2022-03-30 14:09:31', 2, 205, NULL),
(397, '2022-03-30 14:14:27', '2022-03-30 14:14:27', 1, 206, NULL),
(398, '2022-03-30 14:14:27', '2022-03-30 14:14:27', 2, 206, NULL),
(399, '2022-03-30 14:18:03', '2022-03-30 14:18:03', 1, 207, NULL),
(400, '2022-03-30 14:18:03', '2022-03-30 14:18:03', 2, 207, NULL),
(401, '2022-03-30 14:22:15', '2022-03-30 14:22:15', 1, 208, NULL),
(402, '2022-03-30 14:22:15', '2022-03-30 14:22:15', 2, 208, NULL),
(403, '2022-03-30 14:27:20', '2022-03-30 14:27:20', 1, 209, NULL),
(404, '2022-03-30 14:27:20', '2022-03-30 14:27:20', 2, 209, NULL),
(405, '2022-03-30 14:41:19', '2022-03-30 14:41:19', 1, 210, NULL),
(406, '2022-03-30 14:41:19', '2022-03-30 14:41:19', 2, 210, NULL),
(407, '2022-03-30 14:48:30', '2022-03-30 14:48:30', 1, 211, NULL),
(408, '2022-03-30 14:48:30', '2022-03-30 14:48:30', 2, 211, NULL),
(409, '2022-03-30 14:53:35', '2022-03-30 14:53:35', 1, 212, NULL),
(410, '2022-03-30 14:53:35', '2022-03-30 14:53:35', 2, 212, NULL),
(411, '2022-03-30 14:58:15', '2022-03-30 14:58:15', 1, 213, NULL),
(412, '2022-03-30 14:58:15', '2022-03-30 14:58:15', 2, 213, NULL),
(413, '2022-03-30 15:03:08', '2022-03-30 15:03:08', 1, 214, NULL),
(414, '2022-03-30 15:03:08', '2022-03-30 15:03:08', 2, 214, NULL),
(415, '2022-03-30 15:08:45', '2022-03-30 15:08:45', 1, 215, NULL),
(416, '2022-03-30 15:08:45', '2022-03-30 15:08:45', 2, 215, NULL),
(417, '2022-03-30 15:14:06', '2022-03-30 15:14:06', 1, 216, NULL),
(418, '2022-03-30 15:14:06', '2022-03-30 15:14:06', 2, 216, NULL),
(419, '2022-03-30 15:18:10', '2022-03-30 15:18:10', 1, 217, NULL),
(420, '2022-03-30 15:18:10', '2022-03-30 15:18:10', 2, 217, NULL),
(421, '2022-03-30 15:22:36', '2022-03-30 15:22:36', 1, 218, NULL),
(422, '2022-03-30 15:22:36', '2022-03-30 15:22:36', 2, 218, NULL),
(423, '2022-03-30 15:33:15', '2022-03-30 15:33:15', 1, 219, NULL),
(424, '2022-03-30 15:33:15', '2022-03-30 15:33:15', 2, 219, NULL),
(425, '2022-03-30 15:37:30', '2022-03-30 15:37:30', 1, 220, NULL),
(426, '2022-03-30 15:37:30', '2022-03-30 15:37:30', 2, 220, NULL),
(427, '2022-03-30 15:40:45', '2022-03-30 15:40:45', 1, 221, NULL),
(428, '2022-03-30 15:40:45', '2022-03-30 15:40:45', 2, 221, NULL),
(429, '2022-03-30 15:45:02', '2022-03-30 15:45:02', 1, 222, NULL),
(430, '2022-03-30 15:45:02', '2022-03-30 15:45:02', 2, 222, NULL),
(431, '2022-03-30 15:49:09', '2022-03-30 15:49:09', 1, 223, NULL),
(432, '2022-03-30 15:49:09', '2022-03-30 15:49:09', 2, 223, NULL),
(433, '2022-03-30 15:52:31', '2022-03-30 15:52:31', 1, 224, NULL),
(434, '2022-03-30 15:52:31', '2022-03-30 15:52:31', 2, 224, NULL),
(435, '2022-03-30 15:59:17', '2022-03-30 15:59:17', 1, 225, NULL),
(436, '2022-03-30 15:59:17', '2022-03-30 15:59:17', 2, 225, NULL),
(437, '2022-03-30 16:05:13', '2022-03-30 16:05:13', 1, 226, NULL),
(438, '2022-03-30 16:05:13', '2022-03-30 16:05:13', 2, 226, NULL),
(439, '2022-03-30 16:10:03', '2022-03-30 16:10:03', 1, 227, NULL),
(440, '2022-03-30 16:10:03', '2022-03-30 16:10:03', 2, 227, NULL),
(441, '2022-03-30 16:16:17', '2022-03-30 16:16:17', 1, 228, NULL),
(442, '2022-03-30 16:16:17', '2022-03-30 16:16:17', 2, 228, NULL),
(443, '2022-03-30 16:20:51', '2022-03-30 16:20:51', 1, 229, NULL),
(444, '2022-03-30 16:20:51', '2022-03-30 16:20:51', 2, 229, NULL),
(445, '2022-03-30 16:34:27', '2022-03-30 16:34:27', 1, 230, NULL),
(446, '2022-03-30 16:34:27', '2022-03-30 16:34:27', 2, 230, NULL),
(447, '2022-03-30 16:40:12', '2022-03-30 16:40:12', 1, 231, NULL),
(448, '2022-03-30 16:40:12', '2022-03-30 16:40:12', 2, 231, NULL),
(449, '2022-03-30 16:43:53', '2022-03-30 16:43:53', 1, 232, NULL),
(450, '2022-03-30 16:43:53', '2022-03-30 16:43:53', 2, 232, NULL),
(451, '2022-03-30 16:50:44', '2022-03-30 16:50:44', 1, 233, NULL),
(452, '2022-03-30 16:50:44', '2022-03-30 16:50:44', 2, 233, NULL),
(453, '2022-03-30 16:55:50', '2022-03-30 16:55:50', 1, 234, NULL),
(454, '2022-03-30 16:55:50', '2022-03-30 16:55:50', 2, 234, NULL),
(455, '2022-03-30 17:00:44', '2022-03-30 17:00:44', 1, 235, NULL),
(456, '2022-03-30 17:00:44', '2022-03-30 17:00:44', 2, 235, NULL),
(457, '2022-03-30 17:04:29', '2022-03-30 17:04:29', 1, 236, NULL),
(458, '2022-03-30 17:04:29', '2022-03-30 17:04:29', 2, 236, NULL),
(459, '2022-03-30 17:09:25', '2022-03-30 17:09:25', 1, 237, NULL),
(460, '2022-03-30 17:09:25', '2022-03-30 17:09:25', 2, 237, NULL),
(461, '2022-03-30 17:13:19', '2022-03-30 17:13:19', 1, 238, NULL),
(462, '2022-03-30 17:13:19', '2022-03-30 17:13:19', 2, 238, NULL),
(463, '2022-03-30 17:16:50', '2022-03-30 17:16:50', 1, 239, NULL),
(464, '2022-03-30 17:16:50', '2022-03-30 17:16:50', 2, 239, NULL),
(465, '2022-03-30 17:21:35', '2022-03-30 17:21:35', 1, 240, NULL),
(466, '2022-03-30 17:21:35', '2022-03-30 17:21:35', 2, 240, NULL),
(467, '2022-03-30 17:26:26', '2022-03-30 17:26:26', 1, 241, NULL),
(468, '2022-03-30 17:26:26', '2022-03-30 17:26:26', 2, 241, NULL),
(469, '2022-03-30 17:31:13', '2022-03-30 17:31:13', 1, 242, NULL),
(470, '2022-03-30 17:31:13', '2022-03-30 17:31:13', 2, 242, NULL),
(471, '2022-03-31 09:45:01', '2022-03-31 09:45:01', 1, 243, NULL),
(472, '2022-03-31 09:45:01', '2022-03-31 09:45:01', 2, 243, NULL),
(473, '2022-03-31 09:51:47', '2022-03-31 09:51:47', 1, 244, NULL),
(474, '2022-03-31 09:51:47', '2022-03-31 09:51:47', 2, 244, NULL),
(475, '2022-03-31 09:56:04', '2022-03-31 09:56:04', 1, 245, NULL),
(476, '2022-03-31 09:56:04', '2022-03-31 09:56:04', 2, 245, NULL),
(477, '2022-03-31 10:03:41', '2022-03-31 10:03:41', 1, 246, NULL),
(478, '2022-03-31 10:03:41', '2022-03-31 10:03:41', 2, 246, NULL),
(479, '2022-03-31 10:08:47', '2022-03-31 10:08:47', 1, 247, NULL),
(480, '2022-03-31 10:08:47', '2022-03-31 10:08:47', 2, 247, NULL),
(481, '2022-03-31 10:22:34', '2022-03-31 10:22:34', 1, 248, NULL),
(482, '2022-03-31 10:22:34', '2022-03-31 10:22:34', 2, 248, NULL),
(483, '2022-03-31 10:34:43', '2022-03-31 10:34:43', 1, 249, NULL),
(484, '2022-03-31 10:34:43', '2022-03-31 10:34:43', 2, 249, NULL),
(485, '2022-03-31 10:42:57', '2022-03-31 10:42:57', 1, 250, NULL),
(486, '2022-03-31 10:42:57', '2022-03-31 10:42:57', 2, 250, NULL),
(487, '2022-03-31 10:50:09', '2022-03-31 10:50:09', 1, 251, NULL),
(488, '2022-03-31 10:50:09', '2022-03-31 10:50:09', 2, 251, NULL),
(489, '2022-03-31 10:53:44', '2022-03-31 10:53:44', 1, 252, NULL),
(490, '2022-03-31 10:53:44', '2022-03-31 10:53:44', 2, 252, NULL),
(491, '2022-03-31 10:57:21', '2022-03-31 10:57:21', 1, 253, NULL),
(492, '2022-03-31 10:57:21', '2022-03-31 10:57:21', 2, 253, NULL),
(493, '2022-03-31 11:00:49', '2022-03-31 11:00:49', 1, 254, NULL),
(494, '2022-03-31 11:00:49', '2022-03-31 11:00:49', 2, 254, NULL),
(495, '2022-03-31 11:05:19', '2022-03-31 11:05:19', 1, 255, NULL),
(496, '2022-03-31 11:05:19', '2022-03-31 11:05:19', 2, 255, NULL),
(497, '2022-03-31 11:10:26', '2022-03-31 11:10:26', 1, 256, NULL),
(498, '2022-03-31 11:10:26', '2022-03-31 11:10:26', 2, 256, NULL),
(499, '2022-03-31 11:13:28', '2022-03-31 11:13:28', 1, 257, NULL),
(500, '2022-03-31 11:13:28', '2022-03-31 11:13:28', 2, 257, NULL),
(501, '2022-03-31 11:16:27', '2022-03-31 11:16:27', 1, 258, NULL),
(502, '2022-03-31 11:16:27', '2022-03-31 11:16:27', 2, 258, NULL),
(503, '2022-03-31 11:20:41', '2022-03-31 11:20:41', 1, 259, NULL),
(504, '2022-03-31 11:20:41', '2022-03-31 11:20:41', 2, 259, NULL),
(505, '2022-03-31 11:26:40', '2022-03-31 11:26:40', 1, 260, NULL),
(506, '2022-03-31 11:26:40', '2022-03-31 11:26:40', 2, 260, NULL),
(507, '2022-03-31 11:29:05', '2022-03-31 11:29:05', 1, 261, NULL),
(508, '2022-03-31 11:29:05', '2022-03-31 11:29:05', 2, 261, NULL),
(509, '2022-03-31 11:34:05', '2022-03-31 11:34:05', 1, 262, NULL),
(510, '2022-03-31 11:34:05', '2022-03-31 11:34:05', 2, 262, NULL),
(511, '2022-03-31 11:37:18', '2022-03-31 11:37:18', 1, 263, NULL),
(512, '2022-03-31 11:37:18', '2022-03-31 11:37:18', 2, 263, NULL),
(513, '2022-03-31 11:40:19', '2022-03-31 11:40:19', 1, 264, NULL),
(514, '2022-03-31 11:40:19', '2022-03-31 11:40:19', 2, 264, NULL),
(515, '2022-03-31 11:47:04', '2022-03-31 11:47:04', 1, 265, NULL),
(516, '2022-03-31 11:47:04', '2022-03-31 11:47:04', 2, 265, NULL),
(517, '2022-03-31 11:50:01', '2022-03-31 11:50:01', 1, 266, NULL),
(518, '2022-03-31 11:50:01', '2022-03-31 11:50:01', 2, 266, NULL),
(519, '2022-03-31 11:52:38', '2022-03-31 11:52:38', 1, 267, NULL),
(520, '2022-03-31 11:52:38', '2022-03-31 11:52:38', 2, 267, NULL),
(521, '2022-03-31 11:54:54', '2022-03-31 11:54:54', 1, 268, NULL),
(522, '2022-03-31 11:54:54', '2022-03-31 11:54:54', 2, 268, NULL),
(523, '2022-03-31 11:57:46', '2022-03-31 11:57:46', 1, 269, NULL),
(524, '2022-03-31 11:57:46', '2022-03-31 11:57:46', 2, 269, NULL),
(525, '2022-03-31 12:17:26', '2022-03-31 12:17:26', 1, 270, NULL),
(526, '2022-03-31 12:17:26', '2022-03-31 12:17:26', 2, 270, NULL),
(527, '2022-03-31 12:22:59', '2022-03-31 12:22:59', 2, 271, NULL),
(528, '2022-03-31 12:28:09', '2022-03-31 12:28:09', 1, 272, NULL),
(529, '2022-03-31 12:28:09', '2022-03-31 12:28:09', 2, 272, NULL),
(530, '2022-03-31 12:45:43', '2022-03-31 12:45:43', 1, 273, NULL),
(531, '2022-03-31 12:45:43', '2022-03-31 12:45:43', 2, 273, NULL),
(532, '2022-03-31 12:49:30', '2022-03-31 12:49:30', 1, 274, NULL),
(533, '2022-03-31 12:49:30', '2022-03-31 12:49:30', 2, 274, NULL),
(534, '2022-03-31 12:53:29', '2022-03-31 12:53:29', 1, 275, NULL),
(535, '2022-03-31 12:53:29', '2022-03-31 12:53:29', 2, 275, NULL),
(536, '2022-03-31 13:08:36', '2022-03-31 13:08:36', 1, 276, NULL),
(537, '2022-03-31 13:08:36', '2022-03-31 13:08:36', 2, 276, NULL),
(538, '2022-03-31 13:43:08', '2022-03-31 13:43:08', 1, 277, NULL),
(539, '2022-03-31 13:43:08', '2022-03-31 13:43:08', 2, 277, NULL),
(540, '2022-03-31 13:55:18', '2022-03-31 13:55:18', 2, 278, NULL),
(541, '2022-03-31 13:58:47', '2022-03-31 13:58:47', 1, 279, NULL),
(542, '2022-03-31 13:58:47', '2022-03-31 13:58:47', 2, 279, NULL),
(543, '2022-03-31 14:01:44', '2022-03-31 14:01:44', 1, 280, NULL),
(544, '2022-03-31 14:01:44', '2022-03-31 14:01:44', 2, 280, NULL),
(545, '2022-03-31 14:05:59', '2022-03-31 14:05:59', 1, 281, NULL),
(546, '2022-03-31 14:06:00', '2022-03-31 14:06:00', 2, 281, NULL),
(547, '2022-03-31 14:40:25', '2022-03-31 14:40:25', 2, 282, NULL),
(548, '2022-03-31 14:47:11', '2022-03-31 14:47:11', 1, 283, NULL),
(549, '2022-03-31 14:47:11', '2022-03-31 14:47:11', 2, 283, NULL),
(550, '2022-03-31 14:51:04', '2022-03-31 14:51:04', 2, 284, NULL),
(551, '2022-03-31 14:56:47', '2022-03-31 14:56:47', 1, 285, NULL),
(552, '2022-03-31 14:56:47', '2022-03-31 14:56:47', 2, 285, NULL),
(553, '2022-03-31 15:00:46', '2022-03-31 15:00:46', 1, 286, NULL),
(554, '2022-03-31 15:00:46', '2022-03-31 15:00:46', 2, 286, NULL),
(555, '2022-03-31 15:04:27', '2022-03-31 15:04:27', 1, 287, NULL),
(556, '2022-03-31 15:04:27', '2022-03-31 15:04:27', 2, 287, NULL),
(557, '2022-03-31 15:09:10', '2022-03-31 15:09:10', 2, 288, NULL),
(558, '2022-03-31 15:15:04', '2022-03-31 15:15:04', 1, 289, NULL),
(559, '2022-03-31 15:15:04', '2022-03-31 15:15:04', 2, 289, NULL),
(560, '2022-03-31 15:19:32', '2022-03-31 15:19:32', 2, 290, NULL),
(561, '2022-03-31 15:23:38', '2022-03-31 15:23:38', 1, 291, NULL),
(562, '2022-03-31 15:23:38', '2022-03-31 15:23:38', 2, 291, NULL),
(563, '2022-03-31 15:26:40', '2022-03-31 15:26:40', 2, 292, NULL),
(564, '2022-03-31 15:30:38', '2022-03-31 15:30:38', 2, 293, NULL),
(565, '2022-03-31 15:36:07', '2022-03-31 15:36:07', 1, 294, NULL),
(566, '2022-03-31 15:36:07', '2022-03-31 15:36:07', 2, 294, NULL),
(567, '2022-03-31 15:39:18', '2022-03-31 15:39:18', 2, 295, NULL),
(568, '2022-03-31 15:42:16', '2022-03-31 15:42:16', 1, 296, NULL),
(569, '2022-03-31 15:42:16', '2022-03-31 15:42:16', 2, 296, NULL),
(570, '2022-03-31 15:45:12', '2022-03-31 15:45:12', 2, 297, NULL),
(571, '2022-03-31 15:49:11', '2022-03-31 15:49:11', 2, 298, NULL),
(572, '2022-03-31 16:00:38', '2022-03-31 16:00:38', 1, 299, NULL),
(573, '2022-03-31 16:00:38', '2022-03-31 16:00:38', 2, 299, NULL),
(574, '2022-03-31 16:05:51', '2022-03-31 16:05:51', 1, 300, NULL),
(575, '2022-03-31 16:05:51', '2022-03-31 16:05:51', 2, 300, NULL),
(576, '2022-03-31 16:09:23', '2022-03-31 16:09:23', 2, 301, NULL),
(577, '2022-03-31 16:15:59', '2022-03-31 16:15:59', 1, 302, NULL),
(578, '2022-03-31 16:15:59', '2022-03-31 16:15:59', 2, 302, NULL),
(579, '2022-03-31 16:18:13', '2022-03-31 16:18:13', 1, 303, NULL),
(580, '2022-03-31 16:18:13', '2022-03-31 16:18:13', 2, 303, NULL),
(581, '2022-03-31 16:36:04', '2022-03-31 16:36:04', 1, 304, NULL),
(582, '2022-03-31 16:36:04', '2022-03-31 16:36:04', 2, 304, NULL),
(583, '2022-03-31 16:40:00', '2022-03-31 16:40:00', 1, 305, NULL),
(584, '2022-03-31 16:40:00', '2022-03-31 16:40:00', 2, 305, NULL),
(585, '2022-03-31 16:47:13', '2022-03-31 16:47:13', 1, 306, NULL),
(586, '2022-03-31 16:47:13', '2022-03-31 16:47:13', 2, 306, NULL),
(587, '2022-03-31 16:51:08', '2022-03-31 16:51:08', 1, 307, NULL),
(588, '2022-03-31 16:51:08', '2022-03-31 16:51:08', 2, 307, NULL),
(589, '2022-03-31 16:54:47', '2022-03-31 16:54:47', 1, 308, NULL),
(590, '2022-03-31 16:54:47', '2022-03-31 16:54:47', 2, 308, NULL),
(591, '2022-03-31 16:58:24', '2022-03-31 16:58:24', 1, 309, NULL),
(592, '2022-03-31 16:58:24', '2022-03-31 16:58:24', 2, 309, NULL),
(593, '2022-03-31 17:04:12', '2022-03-31 17:04:12', 1, 310, NULL),
(594, '2022-03-31 17:04:12', '2022-03-31 17:04:12', 2, 310, NULL),
(595, '2022-03-31 17:08:11', '2022-03-31 17:08:11', 1, 311, NULL),
(596, '2022-03-31 17:08:11', '2022-03-31 17:08:11', 2, 311, NULL),
(597, '2022-03-31 17:22:41', '2022-03-31 17:22:41', 1, 312, NULL),
(598, '2022-03-31 17:22:41', '2022-03-31 17:22:41', 2, 312, NULL),
(599, '2022-03-31 17:24:21', '2022-03-31 17:24:21', 1, 313, NULL),
(600, '2022-03-31 17:24:21', '2022-03-31 17:24:21', 2, 313, NULL),
(601, '2022-03-31 17:32:47', '2022-03-31 17:32:47', 1, 314, NULL),
(602, '2022-03-31 17:32:47', '2022-03-31 17:32:47', 2, 314, NULL),
(603, '2022-03-31 17:35:45', '2022-03-31 17:35:45', 1, 315, NULL),
(604, '2022-03-31 17:35:45', '2022-03-31 17:35:45', 2, 315, NULL),
(605, '2022-03-31 17:42:51', '2022-03-31 17:42:51', 1, 316, NULL),
(606, '2022-03-31 17:42:51', '2022-03-31 17:42:51', 2, 316, NULL),
(607, '2022-03-31 17:47:29', '2022-03-31 17:47:29', 1, 317, NULL),
(608, '2022-03-31 17:47:29', '2022-03-31 17:47:29', 2, 317, NULL),
(609, '2022-03-31 17:52:53', '2022-03-31 17:52:53', 1, 318, NULL),
(610, '2022-03-31 17:52:53', '2022-03-31 17:52:53', 2, 318, NULL),
(611, '2022-03-31 17:57:36', '2022-03-31 17:57:36', 1, 319, NULL),
(612, '2022-03-31 17:57:36', '2022-03-31 17:57:36', 2, 319, NULL),
(613, '2022-04-01 09:46:59', '2022-04-01 09:46:59', 1, 320, NULL),
(614, '2022-04-01 09:46:59', '2022-04-01 09:46:59', 2, 320, NULL),
(615, '2022-04-01 09:52:56', '2022-04-01 09:52:56', 1, 321, NULL),
(616, '2022-04-01 09:52:56', '2022-04-01 09:52:56', 2, 321, NULL),
(617, '2022-04-01 09:59:43', '2022-04-01 09:59:43', 1, 322, NULL),
(618, '2022-04-01 09:59:43', '2022-04-01 09:59:43', 2, 322, NULL),
(619, '2022-04-01 10:03:48', '2022-04-01 10:03:48', 1, 323, NULL),
(620, '2022-04-01 10:03:48', '2022-04-01 10:03:48', 2, 323, NULL),
(621, '2022-04-01 10:09:08', '2022-04-01 10:09:08', 1, 324, NULL),
(622, '2022-04-01 10:09:08', '2022-04-01 10:09:08', 2, 324, NULL),
(623, '2022-04-01 10:13:37', '2022-04-01 10:13:37', 1, 325, NULL),
(624, '2022-04-01 10:13:37', '2022-04-01 10:13:37', 2, 325, NULL),
(625, '2022-04-01 10:18:12', '2022-04-01 10:18:12', 1, 326, NULL),
(626, '2022-04-01 10:18:12', '2022-04-01 10:18:12', 2, 326, NULL),
(627, '2022-04-01 10:20:56', '2022-04-01 10:20:56', 1, 327, NULL),
(628, '2022-04-01 10:20:56', '2022-04-01 10:20:56', 2, 327, NULL),
(629, '2022-04-01 10:24:54', '2022-04-01 10:24:54', 1, 328, NULL),
(630, '2022-04-01 10:24:54', '2022-04-01 10:24:54', 2, 328, NULL),
(631, '2022-04-01 10:31:35', '2022-04-01 10:31:35', 1, 329, NULL),
(632, '2022-04-01 10:31:35', '2022-04-01 10:31:35', 2, 329, NULL),
(633, '2022-04-01 10:35:39', '2022-04-01 10:35:39', 1, 330, NULL),
(634, '2022-04-01 10:35:39', '2022-04-01 10:35:39', 2, 330, NULL),
(635, '2022-04-01 10:38:16', '2022-04-01 10:38:16', 1, 331, NULL),
(636, '2022-04-01 10:38:16', '2022-04-01 10:38:16', 2, 331, NULL),
(637, '2022-04-01 10:42:01', '2022-04-01 10:42:01', 1, 332, NULL),
(638, '2022-04-01 10:42:01', '2022-04-01 10:42:01', 2, 332, NULL),
(639, '2022-04-01 10:46:10', '2022-04-01 10:46:10', 1, 333, NULL),
(640, '2022-04-01 10:46:10', '2022-04-01 10:46:10', 2, 333, NULL),
(641, '2022-04-01 10:50:18', '2022-04-01 10:50:18', 1, 334, NULL),
(642, '2022-04-01 10:50:18', '2022-04-01 10:50:18', 2, 334, NULL),
(643, '2022-04-01 10:54:23', '2022-04-01 10:54:23', 1, 335, NULL),
(644, '2022-04-01 10:54:23', '2022-04-01 10:54:23', 2, 335, NULL),
(645, '2022-04-01 10:58:28', '2022-04-01 10:58:28', 1, 336, NULL),
(646, '2022-04-01 10:58:28', '2022-04-01 10:58:28', 2, 336, NULL),
(647, '2022-04-01 11:01:38', '2022-04-01 11:01:38', 1, 337, NULL),
(648, '2022-04-01 11:01:38', '2022-04-01 11:01:38', 2, 337, NULL),
(649, '2022-04-01 11:05:05', '2022-04-01 11:05:05', 1, 338, NULL),
(650, '2022-04-01 11:05:05', '2022-04-01 11:05:05', 2, 338, NULL),
(651, '2022-04-01 11:13:28', '2022-04-01 11:13:28', 1, 339, NULL),
(652, '2022-04-01 11:13:28', '2022-04-01 11:13:28', 2, 339, NULL),
(653, '2022-04-01 11:17:26', '2022-04-01 11:17:26', 1, 340, NULL),
(654, '2022-04-01 11:17:26', '2022-04-01 11:17:26', 2, 340, NULL),
(655, '2022-04-01 11:21:39', '2022-04-01 11:21:39', 1, 341, NULL),
(656, '2022-04-01 11:21:39', '2022-04-01 11:21:39', 2, 341, NULL),
(657, '2022-04-01 11:24:37', '2022-04-01 11:24:37', 1, 342, NULL),
(658, '2022-04-01 11:24:37', '2022-04-01 11:24:37', 2, 342, NULL),
(659, '2022-04-01 11:28:52', '2022-04-01 11:28:52', 1, 343, NULL),
(660, '2022-04-01 11:28:52', '2022-04-01 11:28:52', 2, 343, NULL),
(661, '2022-04-01 11:33:51', '2022-04-01 11:33:51', 1, 344, NULL),
(662, '2022-04-01 11:33:51', '2022-04-01 11:33:51', 2, 344, NULL),
(663, '2022-04-01 11:37:58', '2022-04-01 11:37:58', 1, 345, NULL),
(664, '2022-04-01 11:37:58', '2022-04-01 11:37:58', 2, 345, NULL),
(665, '2022-04-01 11:45:52', '2022-04-01 11:45:52', 1, 346, NULL),
(666, '2022-04-01 11:45:52', '2022-04-01 11:45:52', 2, 346, NULL),
(667, '2022-04-01 11:51:33', '2022-04-01 11:51:33', 1, 347, NULL),
(668, '2022-04-01 11:51:33', '2022-04-01 11:51:33', 2, 347, NULL),
(669, '2022-04-01 11:55:22', '2022-04-01 11:55:22', 1, 348, NULL),
(670, '2022-04-01 11:55:22', '2022-04-01 11:55:22', 2, 348, NULL),
(671, '2022-04-01 12:00:09', '2022-04-01 12:00:09', 1, 349, NULL),
(672, '2022-04-01 12:00:09', '2022-04-01 12:00:09', 2, 349, NULL),
(673, '2022-04-01 12:04:00', '2022-04-01 12:04:00', 1, 350, NULL),
(674, '2022-04-01 12:04:00', '2022-04-01 12:04:00', 2, 350, NULL),
(675, '2022-04-01 12:11:40', '2022-04-01 12:11:40', 1, 351, NULL),
(676, '2022-04-01 12:11:40', '2022-04-01 12:11:40', 2, 351, NULL),
(677, '2022-04-01 12:15:56', '2022-04-01 12:15:56', 1, 352, NULL),
(678, '2022-04-01 12:15:56', '2022-04-01 12:15:56', 2, 352, NULL),
(679, '2022-04-01 12:21:15', '2022-04-01 12:21:15', 1, 353, NULL),
(680, '2022-04-01 12:21:15', '2022-04-01 12:21:15', 2, 353, NULL),
(681, '2022-04-01 12:25:37', '2022-04-01 12:25:37', 1, 354, NULL),
(682, '2022-04-01 12:25:37', '2022-04-01 12:25:37', 2, 354, NULL),
(683, '2022-04-01 12:28:44', '2022-04-01 12:28:44', 1, 355, NULL),
(684, '2022-04-01 12:28:44', '2022-04-01 12:28:44', 2, 355, NULL),
(685, '2022-04-01 12:31:18', '2022-04-01 12:31:18', 1, 356, NULL),
(686, '2022-04-01 12:31:18', '2022-04-01 12:31:18', 2, 356, NULL),
(687, '2022-04-01 12:34:50', '2022-04-01 12:34:50', 1, 357, NULL),
(688, '2022-04-01 12:34:50', '2022-04-01 12:34:50', 2, 357, NULL),
(689, '2022-04-01 12:41:35', '2022-04-01 12:41:35', 1, 358, NULL),
(690, '2022-04-01 12:41:35', '2022-04-01 12:41:35', 2, 358, NULL),
(691, '2022-04-01 12:44:19', '2022-04-01 12:44:19', 1, 359, NULL),
(692, '2022-04-01 12:44:19', '2022-04-01 12:44:19', 2, 359, NULL),
(693, '2022-04-01 12:49:29', '2022-04-01 12:49:29', 1, 360, NULL),
(694, '2022-04-01 12:49:29', '2022-04-01 12:49:29', 2, 360, NULL),
(695, '2022-04-01 12:52:46', '2022-04-01 12:52:46', 1, 361, NULL),
(696, '2022-04-01 12:52:46', '2022-04-01 12:52:46', 2, 361, NULL),
(697, '2022-04-01 12:55:38', '2022-04-01 12:55:38', 1, 362, NULL),
(698, '2022-04-01 12:55:38', '2022-04-01 12:55:38', 2, 362, NULL),
(699, '2022-04-01 12:59:14', '2022-04-01 12:59:14', 1, 363, NULL),
(700, '2022-04-01 12:59:14', '2022-04-01 12:59:14', 2, 363, NULL),
(701, '2022-04-01 13:04:42', '2022-04-01 13:04:42', 1, 364, NULL),
(702, '2022-04-01 13:04:42', '2022-04-01 13:04:42', 2, 364, NULL),
(703, '2022-04-01 13:08:54', '2022-04-01 13:08:54', 1, 365, NULL),
(704, '2022-04-01 13:08:54', '2022-04-01 13:08:54', 2, 365, NULL),
(705, '2022-04-01 13:16:13', '2022-04-01 13:16:13', 1, 366, NULL),
(706, '2022-04-01 13:16:13', '2022-04-01 13:16:13', 2, 366, NULL),
(707, '2022-04-01 13:19:57', '2022-04-01 13:19:57', 1, 367, NULL),
(708, '2022-04-01 13:19:57', '2022-04-01 13:19:57', 2, 367, NULL),
(709, '2022-04-01 13:23:09', '2022-04-01 13:23:09', 1, 368, NULL),
(710, '2022-04-01 13:23:09', '2022-04-01 13:23:09', 2, 368, NULL),
(711, '2022-04-01 13:27:38', '2022-04-01 13:27:38', 1, 369, NULL),
(712, '2022-04-01 13:27:38', '2022-04-01 13:27:38', 2, 369, NULL),
(713, '2022-04-01 13:31:13', '2022-04-01 13:31:13', 1, 370, NULL),
(714, '2022-04-01 13:31:13', '2022-04-01 13:31:13', 2, 370, NULL),
(715, '2022-04-01 14:03:20', '2022-04-01 14:03:20', 1, 371, NULL),
(716, '2022-04-01 14:03:20', '2022-04-01 14:03:20', 2, 371, NULL),
(717, '2022-04-01 14:05:40', '2022-04-01 14:05:40', 1, 372, NULL),
(718, '2022-04-01 14:05:40', '2022-04-01 14:05:40', 2, 372, NULL),
(719, '2022-04-01 14:11:19', '2022-04-01 14:11:19', 1, 373, NULL),
(720, '2022-04-01 14:11:19', '2022-04-01 14:11:19', 2, 373, NULL),
(721, '2022-04-01 14:15:47', '2022-04-01 14:15:47', 1, 374, NULL),
(722, '2022-04-01 14:15:47', '2022-04-01 14:15:47', 2, 374, NULL),
(723, '2022-04-01 14:18:26', '2022-04-01 14:18:26', 1, 375, NULL),
(724, '2022-04-01 14:18:26', '2022-04-01 14:18:26', 2, 375, NULL),
(725, '2022-04-01 14:21:48', '2022-04-01 14:21:48', 1, 376, NULL),
(726, '2022-04-01 14:21:48', '2022-04-01 14:21:48', 2, 376, NULL),
(727, '2022-04-01 14:25:16', '2022-04-01 14:25:16', 1, 377, NULL),
(728, '2022-04-01 14:25:16', '2022-04-01 14:25:16', 2, 377, NULL),
(729, '2022-04-01 14:30:57', '2022-04-01 14:30:57', 1, 378, NULL),
(730, '2022-04-01 14:30:57', '2022-04-01 14:30:57', 2, 378, NULL),
(731, '2022-04-01 14:34:07', '2022-04-01 14:34:07', 1, 379, NULL),
(732, '2022-04-01 14:34:07', '2022-04-01 14:34:07', 2, 379, NULL),
(733, '2022-04-01 14:36:55', '2022-04-01 14:36:55', 1, 380, NULL),
(734, '2022-04-01 14:36:55', '2022-04-01 14:36:55', 2, 380, NULL),
(735, '2022-04-01 14:40:17', '2022-04-01 14:40:17', 1, 381, NULL),
(736, '2022-04-01 14:40:17', '2022-04-01 14:40:17', 2, 381, NULL),
(737, '2022-04-01 14:42:50', '2022-04-01 14:42:50', 1, 382, NULL),
(738, '2022-04-01 14:42:50', '2022-04-01 14:42:50', 2, 382, NULL),
(739, '2022-04-01 14:45:23', '2022-04-01 14:45:23', 1, 383, NULL),
(740, '2022-04-01 14:45:23', '2022-04-01 14:45:23', 2, 383, NULL),
(741, '2022-04-01 14:48:18', '2022-04-01 14:48:18', 1, 384, NULL),
(742, '2022-04-01 14:48:18', '2022-04-01 14:48:18', 2, 384, NULL),
(743, '2022-04-01 14:50:29', '2022-04-01 14:50:29', 1, 385, NULL),
(744, '2022-04-01 14:50:29', '2022-04-01 14:50:29', 2, 385, NULL),
(745, '2022-04-01 14:54:55', '2022-04-01 14:54:55', 1, 386, NULL),
(746, '2022-04-01 14:54:55', '2022-04-01 14:54:55', 2, 386, NULL),
(747, '2022-04-01 14:57:33', '2022-04-01 14:57:33', 1, 387, NULL),
(748, '2022-04-01 14:57:33', '2022-04-01 14:57:33', 2, 387, NULL),
(749, '2022-04-01 14:59:44', '2022-04-01 14:59:44', 1, 388, NULL),
(750, '2022-04-01 14:59:44', '2022-04-01 14:59:44', 2, 388, NULL),
(751, '2022-04-01 15:02:26', '2022-04-01 15:02:26', 1, 389, NULL),
(752, '2022-04-01 15:02:26', '2022-04-01 15:02:26', 2, 389, NULL),
(753, '2022-04-01 15:44:06', '2022-04-01 15:44:06', 1, 390, NULL),
(754, '2022-04-01 15:44:06', '2022-04-01 15:44:06', 2, 390, NULL),
(755, '2022-04-01 15:49:56', '2022-04-01 15:49:56', 1, 391, NULL),
(756, '2022-04-01 15:53:54', '2022-04-01 15:53:54', 1, 392, NULL),
(757, '2022-04-01 15:53:54', '2022-04-01 15:53:54', 2, 392, NULL),
(758, '2022-04-01 15:57:17', '2022-04-01 15:57:17', 1, 393, NULL),
(759, '2022-04-01 15:57:17', '2022-04-01 15:57:17', 2, 393, NULL),
(760, '2022-04-01 16:01:04', '2022-04-01 16:01:04', 1, 394, NULL),
(761, '2022-04-01 16:01:04', '2022-04-01 16:01:04', 2, 394, NULL),
(762, '2022-04-01 16:04:31', '2022-04-01 16:04:31', 1, 395, NULL),
(763, '2022-04-01 16:04:31', '2022-04-01 16:04:31', 2, 395, NULL),
(764, '2022-04-01 16:38:33', '2022-04-01 16:38:33', 1, 396, NULL),
(765, '2022-04-01 16:38:33', '2022-04-01 16:38:33', 2, 396, NULL),
(766, '2022-04-01 16:42:23', '2022-04-01 16:42:23', 1, 397, NULL),
(767, '2022-04-01 16:42:23', '2022-04-01 16:42:23', 2, 397, NULL),
(768, '2022-04-01 16:46:06', '2022-04-01 16:46:06', 1, 398, NULL),
(769, '2022-04-01 16:46:06', '2022-04-01 16:46:06', 2, 398, NULL),
(770, '2022-04-01 16:51:40', '2022-04-01 16:51:40', 1, 399, NULL),
(771, '2022-04-01 16:51:40', '2022-04-01 16:51:40', 2, 399, NULL),
(772, '2022-04-01 16:54:46', '2022-04-01 16:54:46', 1, 400, NULL);
INSERT INTO `SERVICIO_CONTRATO` (`SRC_CODIGO`, `SRC_CREATED`, `SRC_UPDATED`, `SRV_CODIGO`, `CTO_CODIGO`, `SRC_DELETED`) VALUES
(773, '2022-04-01 16:54:46', '2022-04-01 16:54:46', 2, 400, NULL),
(774, '2022-04-01 16:57:45', '2022-04-01 16:57:45', 1, 401, NULL),
(775, '2022-04-01 16:57:45', '2022-04-01 16:57:45', 2, 401, NULL),
(776, '2022-04-01 17:01:13', '2022-04-01 17:01:13', 1, 402, NULL),
(777, '2022-04-01 17:01:13', '2022-04-01 17:01:13', 2, 402, NULL),
(778, '2022-04-01 17:05:29', '2022-04-01 17:05:29', 1, 403, NULL),
(779, '2022-04-01 17:05:29', '2022-04-01 17:05:29', 2, 403, NULL),
(780, '2022-04-01 17:15:40', '2022-04-01 17:15:40', 1, 404, NULL),
(781, '2022-04-01 17:15:40', '2022-04-01 17:15:40', 2, 404, NULL),
(782, '2022-04-01 17:18:40', '2022-04-01 17:18:40', 1, 405, NULL),
(783, '2022-04-01 17:18:40', '2022-04-01 17:18:40', 2, 405, NULL),
(784, '2022-04-01 17:21:14', '2022-04-01 17:21:14', 1, 406, NULL),
(785, '2022-04-01 17:21:14', '2022-04-01 17:21:14', 2, 406, NULL),
(786, '2022-04-01 17:24:02', '2022-04-01 17:24:02', 1, 407, NULL),
(787, '2022-04-01 17:24:02', '2022-04-01 17:24:02', 2, 407, NULL),
(788, '2022-04-01 17:27:28', '2022-04-01 17:27:28', 1, 408, NULL),
(789, '2022-04-01 17:27:28', '2022-04-01 17:27:28', 2, 408, NULL),
(790, '2022-04-01 17:32:32', '2022-04-01 17:32:32', 1, 409, NULL),
(791, '2022-04-01 17:32:32', '2022-04-01 17:32:32', 2, 409, NULL),
(792, '2022-04-01 17:37:11', '2022-04-01 17:37:11', 1, 410, NULL),
(793, '2022-04-01 17:37:11', '2022-04-01 17:37:11', 2, 410, NULL),
(794, '2022-04-01 17:41:32', '2022-04-01 17:41:32', 1, 411, NULL),
(795, '2022-04-01 17:41:32', '2022-04-01 17:41:32', 2, 411, NULL),
(796, '2022-04-01 17:48:00', '2022-04-01 17:48:00', 1, 412, NULL),
(797, '2022-04-01 17:48:00', '2022-04-01 17:48:00', 2, 412, NULL),
(798, '2022-04-01 17:52:49', '2022-04-01 17:52:49', 1, 413, NULL),
(799, '2022-04-01 17:52:49', '2022-04-01 17:52:49', 2, 413, NULL),
(800, '2022-04-01 17:54:53', '2022-04-01 17:54:53', 1, 414, NULL),
(801, '2022-04-01 17:54:53', '2022-04-01 17:54:53', 2, 414, NULL),
(802, '2022-04-01 17:57:06', '2022-04-01 17:57:06', 1, 415, NULL),
(803, '2022-04-01 17:57:06', '2022-04-01 17:57:06', 2, 415, NULL),
(804, '2022-04-01 18:03:27', '2022-04-01 18:03:27', 1, 416, NULL),
(805, '2022-04-01 18:03:27', '2022-04-01 18:03:27', 2, 416, NULL),
(806, '2022-04-01 18:05:16', '2022-04-01 18:05:16', 1, 417, NULL),
(807, '2022-04-01 18:05:16', '2022-04-01 18:05:16', 2, 417, NULL),
(808, '2022-04-01 18:06:50', '2022-04-01 18:06:50', 1, 418, NULL),
(809, '2022-04-01 18:06:50', '2022-04-01 18:06:50', 2, 418, NULL),
(810, '2022-04-01 18:09:17', '2022-04-01 18:09:17', 1, 419, NULL),
(811, '2022-04-01 18:09:17', '2022-04-01 18:09:17', 2, 419, NULL),
(812, '2022-04-01 18:11:04', '2022-04-01 18:11:04', 1, 420, NULL),
(813, '2022-04-01 18:11:04', '2022-04-01 18:11:04', 2, 420, NULL),
(814, '2022-04-01 18:12:50', '2022-04-01 18:12:50', 1, 421, NULL),
(815, '2022-04-01 18:12:50', '2022-04-01 18:12:50', 2, 421, NULL),
(816, '2022-04-01 18:15:42', '2022-04-01 18:15:42', 1, 422, NULL),
(817, '2022-04-01 18:15:42', '2022-04-01 18:15:42', 2, 422, NULL),
(818, '2022-04-01 18:17:34', '2022-04-01 18:17:34', 1, 423, NULL),
(819, '2022-04-01 18:17:34', '2022-04-01 18:17:34', 2, 423, NULL),
(820, '2022-04-01 18:19:12', '2022-04-01 18:19:12', 1, 424, NULL),
(821, '2022-04-01 18:19:12', '2022-04-01 18:19:12', 2, 424, NULL),
(822, '2022-04-03 15:57:56', '2022-04-03 15:57:56', 1, 425, NULL),
(823, '2022-04-03 15:57:56', '2022-04-03 15:57:56', 2, 425, NULL),
(824, '2022-04-03 16:02:26', '2022-04-03 16:02:26', 1, 426, NULL),
(825, '2022-04-03 16:02:26', '2022-04-03 16:02:26', 2, 426, NULL),
(826, '2022-04-03 16:07:27', '2022-04-03 16:07:27', 1, 427, NULL),
(827, '2022-04-03 16:07:27', '2022-04-03 16:07:27', 2, 427, NULL),
(828, '2022-04-03 16:13:37', '2022-04-03 16:13:37', 1, 428, NULL),
(829, '2022-04-03 16:13:37', '2022-04-03 16:13:37', 2, 428, NULL),
(830, '2022-04-03 16:20:42', '2022-04-03 16:20:42', 1, 429, NULL),
(831, '2022-04-03 16:20:42', '2022-04-03 16:20:42', 2, 429, NULL),
(832, '2022-04-03 16:26:49', '2022-04-03 16:26:49', 1, 430, NULL),
(833, '2022-04-03 16:26:49', '2022-04-03 16:26:49', 2, 430, NULL),
(834, '2022-04-03 16:33:02', '2022-04-03 16:33:02', 1, 431, NULL),
(835, '2022-04-03 16:33:02', '2022-04-03 16:33:02', 2, 431, NULL),
(836, '2022-04-03 16:35:43', '2022-04-03 16:35:43', 1, 432, NULL),
(837, '2022-04-03 16:35:43', '2022-04-03 16:35:43', 2, 432, NULL),
(838, '2022-04-03 16:39:44', '2022-04-03 16:39:44', 1, 433, NULL),
(839, '2022-04-03 16:39:44', '2022-04-03 16:39:44', 2, 433, NULL),
(840, '2022-04-03 16:49:58', '2022-04-03 16:49:58', 1, 434, NULL),
(841, '2022-04-03 16:49:58', '2022-04-03 16:49:58', 2, 434, NULL),
(842, '2022-04-03 17:17:35', '2022-04-03 17:17:35', 1, 435, NULL),
(843, '2022-04-03 17:17:35', '2022-04-03 17:17:35', 2, 435, NULL),
(844, '2022-04-03 17:22:45', '2022-04-03 17:22:45', 1, 436, NULL),
(845, '2022-04-03 17:22:45', '2022-04-03 17:22:45', 2, 436, NULL),
(846, '2022-04-03 17:24:48', '2022-04-03 17:24:48', 1, 437, NULL),
(847, '2022-04-03 17:24:48', '2022-04-03 17:24:48', 2, 437, NULL),
(848, '2022-04-03 17:27:47', '2022-04-03 17:27:47', 1, 438, NULL),
(849, '2022-04-03 17:27:47', '2022-04-03 17:27:47', 2, 438, NULL),
(850, '2022-04-03 17:31:39', '2022-04-03 17:31:39', 1, 439, NULL),
(851, '2022-04-03 17:31:39', '2022-04-03 17:31:39', 2, 439, NULL),
(852, '2022-04-03 17:34:59', '2022-04-03 17:34:59', 1, 440, NULL),
(853, '2022-04-03 17:34:59', '2022-04-03 17:34:59', 2, 440, NULL),
(854, '2022-04-03 17:40:26', '2022-04-03 17:40:26', 1, 441, NULL),
(855, '2022-04-03 17:40:26', '2022-04-03 17:40:26', 2, 441, NULL),
(856, '2022-04-03 17:52:19', '2022-04-03 17:52:19', 2, 442, NULL),
(857, '2022-04-03 17:56:20', '2022-04-03 17:56:20', 1, 443, NULL),
(858, '2022-04-03 17:56:20', '2022-04-03 17:56:20', 2, 443, NULL),
(859, '2022-04-03 17:59:16', '2022-04-03 17:59:16', 1, 444, NULL),
(860, '2022-04-03 17:59:16', '2022-04-03 17:59:16', 2, 444, NULL),
(861, '2022-04-03 18:13:32', '2022-04-03 18:13:32', 2, 445, NULL),
(862, '2022-04-03 18:19:04', '2022-04-03 18:19:04', 1, 446, NULL),
(863, '2022-04-03 18:19:04', '2022-04-03 18:19:04', 2, 446, NULL),
(864, '2022-04-03 18:21:50', '2022-04-03 18:21:50', 1, 447, NULL),
(865, '2022-04-03 18:21:50', '2022-04-03 18:21:50', 2, 447, NULL),
(866, '2022-04-03 18:50:09', '2022-04-03 18:50:09', 1, 448, NULL),
(867, '2022-04-03 18:50:09', '2022-04-03 18:50:09', 2, 448, NULL),
(868, '2022-04-03 19:02:13', '2022-04-03 19:02:13', 1, 449, NULL),
(869, '2022-04-03 19:02:13', '2022-04-03 19:02:13', 2, 449, NULL),
(870, '2022-04-03 19:10:37', '2022-04-03 19:10:37', 1, 450, NULL),
(871, '2022-04-03 19:10:37', '2022-04-03 19:10:37', 2, 450, NULL),
(872, '2022-04-03 19:14:03', '2022-04-03 19:14:03', 1, 451, NULL),
(873, '2022-04-03 19:14:03', '2022-04-03 19:14:03', 2, 451, NULL),
(874, '2022-04-03 19:17:05', '2022-04-03 19:17:05', 1, 452, NULL),
(875, '2022-04-03 19:20:26', '2022-04-03 19:20:26', 1, 453, NULL),
(876, '2022-04-03 19:20:26', '2022-04-03 19:20:26', 2, 453, NULL),
(877, '2022-04-03 19:26:25', '2022-04-03 19:26:25', 1, 454, NULL),
(878, '2022-04-03 19:26:25', '2022-04-03 19:26:25', 2, 454, NULL),
(879, '2022-04-03 19:57:26', '2022-04-03 19:57:26', 1, 455, NULL),
(880, '2022-04-03 19:57:26', '2022-04-03 19:57:26', 2, 455, NULL),
(881, '2022-04-03 20:00:08', '2022-04-03 20:00:08', 1, 456, NULL),
(882, '2022-04-03 20:00:08', '2022-04-03 20:00:08', 2, 456, NULL),
(883, '2022-04-03 20:03:05', '2022-04-03 20:03:05', 1, 457, NULL),
(884, '2022-04-03 20:03:05', '2022-04-03 20:03:05', 2, 457, NULL),
(885, '2022-04-03 20:15:53', '2022-04-03 20:15:53', 1, 458, NULL),
(886, '2022-04-03 20:15:53', '2022-04-03 20:15:53', 2, 458, NULL),
(887, '2022-04-03 20:20:48', '2022-04-03 20:20:48', 1, 459, NULL),
(888, '2022-04-03 20:20:48', '2022-04-03 20:20:48', 2, 459, NULL),
(889, '2022-04-03 20:23:37', '2022-04-03 20:23:37', 1, 460, NULL),
(890, '2022-04-03 20:23:37', '2022-04-03 20:23:37', 2, 460, NULL),
(891, '2022-04-03 20:28:05', '2022-04-03 20:28:05', 1, 461, NULL),
(892, '2022-04-03 20:28:05', '2022-04-03 20:28:05', 2, 461, NULL),
(893, '2022-04-03 20:29:04', '2022-04-03 20:29:04', 1, 462, NULL),
(894, '2022-04-03 20:29:04', '2022-04-03 20:29:04', 2, 462, NULL),
(895, '2022-04-03 20:32:40', '2022-04-03 20:32:40', 1, 463, NULL),
(896, '2022-04-03 20:32:40', '2022-04-03 20:32:40', 2, 463, NULL),
(897, '2022-04-03 20:35:24', '2022-04-03 20:35:24', 1, 464, NULL),
(898, '2022-04-03 20:35:24', '2022-04-03 20:35:24', 2, 464, NULL),
(899, '2022-04-03 20:38:17', '2022-04-03 20:38:17', 1, 465, NULL),
(900, '2022-04-03 20:38:17', '2022-04-03 20:38:17', 2, 465, NULL),
(901, '2022-04-03 20:43:14', '2022-04-03 20:43:14', 1, 466, NULL),
(902, '2022-04-03 20:43:14', '2022-04-03 20:43:14', 2, 466, NULL),
(903, '2022-04-03 20:49:37', '2022-04-03 20:49:37', 1, 467, NULL),
(904, '2022-04-03 20:49:37', '2022-04-03 20:49:37', 2, 467, NULL),
(905, '2022-04-03 20:56:58', '2022-04-03 20:56:58', 1, 468, NULL),
(906, '2022-04-03 20:56:58', '2022-04-03 20:56:58', 2, 468, NULL),
(907, '2022-04-03 21:00:47', '2022-04-03 21:00:47', 1, 469, NULL),
(908, '2022-04-03 21:00:47', '2022-04-03 21:00:47', 2, 469, NULL),
(909, '2022-04-03 21:03:36', '2022-04-03 21:03:36', 1, 470, NULL),
(910, '2022-04-03 21:03:36', '2022-04-03 21:03:36', 2, 470, NULL),
(911, '2022-04-03 21:06:18', '2022-04-03 21:06:18', 1, 471, NULL),
(912, '2022-04-03 21:06:18', '2022-04-03 21:06:18', 2, 471, NULL),
(913, '2022-04-03 21:08:47', '2022-04-03 21:08:47', 1, 472, NULL),
(914, '2022-04-03 21:08:47', '2022-04-03 21:08:47', 2, 472, NULL),
(915, '2022-04-03 21:11:49', '2022-04-03 21:11:49', 1, 473, NULL),
(916, '2022-04-03 21:11:49', '2022-04-03 21:11:49', 2, 473, NULL),
(917, '2022-04-03 21:15:43', '2022-04-03 21:15:43', 1, 474, NULL),
(918, '2022-04-03 21:15:43', '2022-04-03 21:15:43', 2, 474, NULL),
(919, '2022-04-03 21:18:03', '2022-04-03 21:18:03', 1, 475, NULL),
(920, '2022-04-03 21:18:03', '2022-04-03 21:18:03', 2, 475, NULL),
(921, '2022-04-03 21:20:08', '2022-04-03 21:20:08', 1, 476, NULL),
(922, '2022-04-03 21:20:08', '2022-04-03 21:20:08', 2, 476, NULL),
(923, '2022-04-03 21:45:06', '2022-04-03 21:45:06', 2, 477, NULL),
(924, '2022-04-03 21:49:07', '2022-04-03 21:49:07', 1, 478, NULL),
(925, '2022-04-03 21:49:07', '2022-04-03 21:49:07', 2, 478, NULL),
(926, '2022-04-03 22:22:43', '2022-04-03 22:22:43', 1, 479, NULL),
(927, '2022-04-03 22:22:43', '2022-04-03 22:22:43', 2, 479, NULL),
(928, '2022-04-03 22:25:57', '2022-04-03 22:25:57', 1, 480, NULL),
(929, '2022-04-03 22:25:57', '2022-04-03 22:25:57', 2, 480, NULL),
(930, '2022-04-03 22:29:12', '2022-04-03 22:29:12', 1, 481, NULL),
(931, '2022-04-03 22:29:12', '2022-04-03 22:29:12', 2, 481, NULL),
(932, '2022-04-03 22:34:50', '2022-04-03 22:34:50', 1, 482, NULL),
(933, '2022-04-03 22:38:21', '2022-04-03 22:38:21', 1, 483, NULL),
(934, '2022-04-03 22:45:09', '2022-04-03 22:45:09', 2, 484, NULL),
(935, '2022-04-03 22:49:30', '2022-04-03 22:49:30', 1, 485, NULL),
(936, '2022-04-03 22:49:30', '2022-04-03 22:49:30', 2, 485, NULL),
(937, '2022-04-03 23:26:13', '2022-04-03 23:26:13', 1, 486, NULL),
(938, '2022-04-03 23:26:13', '2022-04-03 23:26:13', 2, 486, NULL),
(939, '2022-04-03 23:56:50', '2022-04-03 23:56:50', 1, 487, NULL),
(940, '2022-04-03 23:56:50', '2022-04-03 23:56:50', 2, 487, NULL),
(941, '2022-04-04 00:00:12', '2022-04-04 00:00:12', 2, 488, NULL),
(942, '2022-04-04 00:04:05', '2022-04-04 00:04:05', 2, 489, NULL),
(943, '2022-04-04 00:06:50', '2022-04-04 00:06:50', 1, 490, NULL),
(944, '2022-04-04 00:06:50', '2022-04-04 00:06:50', 2, 490, NULL),
(945, '2022-04-04 00:10:01', '2022-04-04 00:10:01', 2, 491, NULL),
(946, '2022-04-04 00:12:47', '2022-04-04 00:12:47', 2, 492, NULL),
(947, '2022-04-04 00:15:32', '2022-04-04 00:15:32', 2, 493, NULL),
(948, '2022-04-04 00:21:40', '2022-04-04 00:21:40', 2, 494, NULL),
(949, '2022-04-04 00:24:53', '2022-04-04 00:24:53', 1, 495, NULL),
(950, '2022-04-04 00:24:53', '2022-04-04 00:24:53', 2, 495, NULL),
(951, '2022-04-04 00:28:41', '2022-04-04 00:28:41', 1, 496, NULL),
(952, '2022-04-04 00:28:41', '2022-04-04 00:28:41', 2, 496, NULL),
(953, '2022-04-04 00:31:55', '2022-04-04 00:31:55', 1, 497, NULL),
(954, '2022-04-04 00:31:55', '2022-04-04 00:31:55', 2, 497, NULL),
(955, '2022-04-04 00:35:01', '2022-04-04 00:35:01', 1, 498, NULL),
(956, '2022-04-04 00:35:01', '2022-04-04 00:35:01', 2, 498, NULL),
(957, '2022-04-04 00:38:02', '2022-04-04 00:38:02', 1, 499, NULL),
(958, '2022-04-04 00:38:02', '2022-04-04 00:38:02', 2, 499, NULL),
(959, '2022-04-04 00:42:17', '2022-04-04 00:42:17', 1, 500, NULL),
(960, '2022-04-04 00:42:17', '2022-04-04 00:42:17', 2, 500, NULL),
(961, '2022-04-04 00:45:01', '2022-04-04 00:45:01', 1, 501, NULL),
(962, '2022-04-04 00:45:01', '2022-04-04 00:45:01', 2, 501, NULL),
(963, '2022-04-04 00:47:07', '2022-04-04 00:47:07', 1, 502, NULL),
(964, '2022-04-04 00:47:07', '2022-04-04 00:47:07', 2, 502, NULL),
(965, '2022-04-04 15:56:25', '2022-04-04 15:56:25', 1, 503, NULL),
(966, '2022-04-04 16:02:52', '2022-04-04 16:02:52', 1, 504, NULL),
(967, '2022-04-04 16:11:56', '2022-04-04 16:11:56', 1, 505, NULL),
(968, '2022-04-04 16:19:14', '2022-04-04 16:19:14', 2, 506, NULL),
(969, '2022-04-04 16:25:03', '2022-04-04 16:25:03', 2, 507, NULL),
(970, '2022-04-04 16:30:27', '2022-04-04 16:30:27', 2, 508, NULL),
(971, '2022-04-04 16:44:55', '2022-04-04 16:44:55', 1, 509, NULL),
(972, '2022-04-04 16:44:55', '2022-04-04 16:44:55', 2, 509, NULL),
(973, '2022-04-04 16:49:20', '2022-04-04 16:49:20', 1, 510, NULL),
(974, '2022-04-04 16:49:20', '2022-04-04 16:49:20', 2, 510, NULL),
(975, '2022-04-04 16:54:28', '2022-04-04 16:54:28', 1, 511, NULL),
(976, '2022-04-04 16:54:28', '2022-04-04 16:54:28', 2, 511, NULL),
(977, '2022-04-04 16:59:32', '2022-04-04 16:59:32', 1, 512, NULL),
(978, '2022-04-04 16:59:32', '2022-04-04 16:59:32', 2, 512, NULL),
(979, '2022-04-04 17:02:46', '2022-04-04 17:02:46', 1, 513, NULL),
(980, '2022-04-04 17:02:46', '2022-04-04 17:02:46', 2, 513, NULL),
(981, '2022-04-04 17:10:02', '2022-04-04 17:10:02', 1, 514, NULL),
(982, '2022-04-04 17:10:02', '2022-04-04 17:10:02', 2, 514, NULL),
(983, '2022-04-04 17:22:25', '2022-04-04 17:22:25', 1, 515, NULL),
(984, '2022-04-04 17:25:07', '2022-04-04 17:25:07', 1, 516, NULL),
(985, '2022-04-04 17:29:01', '2022-04-04 17:29:01', 1, 517, NULL),
(986, '2022-04-04 17:29:01', '2022-04-04 17:29:01', 2, 517, NULL),
(987, '2022-04-04 17:31:57', '2022-04-04 17:31:57', 1, 518, NULL),
(988, '2022-04-04 17:35:01', '2022-04-04 17:35:01', 1, 519, NULL),
(989, '2022-04-04 17:35:01', '2022-04-04 17:35:01', 2, 519, NULL),
(990, '2022-04-04 17:38:55', '2022-04-04 17:38:55', 1, 520, NULL),
(991, '2022-04-04 17:42:14', '2022-04-04 17:42:14', 1, 521, NULL),
(992, '2022-04-04 17:46:21', '2022-04-04 17:46:21', 1, 522, NULL),
(993, '2022-04-04 17:55:16', '2022-04-04 17:55:16', 1, 523, NULL),
(994, '2022-04-04 17:59:34', '2022-04-04 17:59:34', 1, 524, NULL),
(995, '2022-04-04 18:02:56', '2022-04-04 18:02:56', 1, 525, NULL),
(996, '2022-04-04 18:02:56', '2022-04-04 18:02:56', 2, 525, NULL),
(997, '2022-04-04 18:05:44', '2022-04-04 18:05:44', 1, 526, NULL),
(998, '2022-04-04 18:05:44', '2022-04-04 18:05:44', 2, 526, NULL),
(999, '2022-04-04 18:09:00', '2022-04-04 18:09:00', 1, 527, NULL),
(1000, '2022-04-04 18:12:26', '2022-04-04 18:12:26', 1, 528, NULL),
(1001, '2022-04-04 18:12:26', '2022-04-04 18:12:26', 2, 528, NULL),
(1002, '2022-04-04 18:16:07', '2022-04-04 18:16:07', 1, 529, NULL),
(1003, '2022-04-04 18:16:07', '2022-04-04 18:16:07', 2, 529, NULL),
(1004, '2022-04-04 18:19:21', '2022-04-04 18:19:21', 1, 530, NULL),
(1005, '2022-04-04 18:21:36', '2022-04-04 18:21:36', 1, 531, NULL),
(1006, '2022-04-04 18:24:46', '2022-04-04 18:24:46', 1, 532, NULL),
(1007, '2022-04-04 18:24:46', '2022-04-04 18:24:46', 2, 532, NULL),
(1008, '2022-04-04 18:27:17', '2022-04-04 18:27:17', 1, 533, NULL),
(1009, '2022-04-04 18:27:17', '2022-04-04 18:27:17', 2, 533, NULL),
(1010, '2022-04-04 18:29:43', '2022-04-04 18:29:43', 2, 534, NULL),
(1011, '2022-04-04 18:46:30', '2022-04-04 18:46:30', 1, 535, NULL),
(1012, '2022-04-04 18:46:30', '2022-04-04 18:46:30', 2, 535, NULL),
(1013, '2022-04-04 18:50:46', '2022-04-04 18:50:46', 1, 536, NULL),
(1014, '2022-04-04 18:50:46', '2022-04-04 18:50:46', 2, 536, NULL),
(1015, '2022-04-04 18:53:32', '2022-04-04 18:53:32', 1, 537, NULL),
(1016, '2022-04-04 18:53:32', '2022-04-04 18:53:32', 2, 537, NULL),
(1017, '2022-04-04 18:58:02', '2022-04-04 18:58:02', 1, 538, NULL),
(1018, '2022-04-04 19:01:36', '2022-04-04 19:01:36', 2, 539, NULL),
(1019, '2022-04-04 19:09:55', '2022-04-04 19:09:55', 1, 540, NULL),
(1020, '2022-04-04 19:13:32', '2022-04-04 19:13:32', 1, 541, NULL),
(1021, '2022-04-04 19:13:32', '2022-04-04 19:13:32', 2, 541, NULL),
(1022, '2022-04-04 19:17:04', '2022-04-04 19:17:04', 1, 542, NULL),
(1023, '2022-04-04 19:17:04', '2022-04-04 19:17:04', 2, 542, NULL),
(1024, '2022-04-04 19:20:09', '2022-04-04 19:20:09', 1, 543, NULL),
(1025, '2022-04-04 19:20:09', '2022-04-04 19:20:09', 2, 543, NULL),
(1026, '2022-04-04 19:23:47', '2022-04-04 19:23:47', 1, 544, NULL),
(1027, '2022-04-04 19:23:47', '2022-04-04 19:23:47', 2, 544, NULL),
(1028, '2022-04-04 19:27:32', '2022-04-04 19:27:32', 2, 545, NULL),
(1029, '2022-04-04 19:31:14', '2022-04-04 19:31:14', 1, 546, NULL),
(1030, '2022-04-04 19:31:14', '2022-04-04 19:31:14', 2, 546, NULL),
(1031, '2022-04-04 19:36:56', '2022-04-04 19:36:56', 1, 547, NULL),
(1032, '2022-04-04 19:36:56', '2022-04-04 19:36:56', 2, 547, NULL),
(1033, '2022-04-04 19:39:49', '2022-04-04 19:39:49', 1, 548, NULL),
(1034, '2022-04-04 19:39:49', '2022-04-04 19:39:49', 2, 548, NULL),
(1035, '2022-04-04 19:43:03', '2022-04-04 19:43:03', 1, 549, NULL),
(1036, '2022-04-04 19:43:03', '2022-04-04 19:43:03', 2, 549, NULL),
(1037, '2022-04-04 19:46:21', '2022-04-04 19:46:21', 1, 550, NULL),
(1038, '2022-04-04 19:46:21', '2022-04-04 19:46:21', 2, 550, NULL),
(1039, '2022-04-04 19:54:28', '2022-04-04 19:54:28', 1, 551, NULL),
(1040, '2022-04-04 19:54:28', '2022-04-04 19:54:28', 2, 551, NULL),
(1041, '2022-04-04 20:01:04', '2022-04-04 20:01:04', 1, 552, NULL),
(1042, '2022-04-04 20:01:04', '2022-04-04 20:01:04', 2, 552, NULL),
(1043, '2022-04-04 20:04:16', '2022-04-04 20:04:16', 1, 553, NULL),
(1044, '2022-04-04 20:04:16', '2022-04-04 20:04:16', 2, 553, NULL),
(1045, '2022-04-04 20:07:26', '2022-04-04 20:07:26', 1, 554, NULL),
(1046, '2022-04-04 20:07:26', '2022-04-04 20:07:26', 2, 554, NULL),
(1047, '2022-04-04 20:12:48', '2022-04-04 20:12:48', 1, 555, NULL),
(1048, '2022-04-04 20:12:48', '2022-04-04 20:12:48', 2, 555, NULL),
(1049, '2022-04-04 20:17:30', '2022-04-04 20:17:30', 2, 556, NULL),
(1050, '2022-04-04 20:21:52', '2022-04-04 20:21:52', 1, 557, NULL),
(1051, '2022-04-04 20:21:52', '2022-04-04 20:21:52', 2, 557, NULL),
(1052, '2022-04-04 20:26:16', '2022-04-04 20:26:16', 1, 558, NULL),
(1053, '2022-04-04 20:26:16', '2022-04-04 20:26:16', 2, 558, NULL),
(1054, '2022-04-04 20:30:53', '2022-04-04 20:30:53', 1, 559, NULL),
(1055, '2022-04-04 20:30:53', '2022-04-04 20:30:53', 2, 559, NULL),
(1056, '2022-04-04 20:34:58', '2022-04-04 20:34:58', 1, 560, NULL),
(1057, '2022-04-04 20:34:58', '2022-04-04 20:34:58', 2, 560, NULL),
(1058, '2022-04-04 20:38:15', '2022-04-04 20:38:15', 1, 561, NULL),
(1059, '2022-04-04 20:38:15', '2022-04-04 20:38:15', 2, 561, NULL),
(1060, '2022-04-04 20:41:21', '2022-04-04 20:41:21', 1, 562, NULL),
(1061, '2022-04-04 20:41:21', '2022-04-04 20:41:21', 2, 562, NULL),
(1062, '2022-04-04 20:44:53', '2022-04-04 20:44:53', 1, 563, NULL),
(1063, '2022-04-04 20:44:53', '2022-04-04 20:44:53', 2, 563, NULL),
(1064, '2022-04-04 20:47:35', '2022-04-04 20:47:35', 1, 564, NULL),
(1065, '2022-04-04 20:47:35', '2022-04-04 20:47:35', 2, 564, NULL),
(1066, '2022-04-04 21:13:57', '2022-04-04 21:13:57', 1, 565, NULL),
(1067, '2022-04-04 21:13:57', '2022-04-04 21:13:57', 2, 565, NULL),
(1068, '2022-04-04 21:20:49', '2022-04-04 21:20:49', 1, 566, NULL),
(1069, '2022-04-04 21:20:49', '2022-04-04 21:20:49', 2, 566, NULL),
(1070, '2022-04-04 21:24:54', '2022-04-04 21:24:54', 1, 567, NULL),
(1071, '2022-04-04 21:24:54', '2022-04-04 21:24:54', 2, 567, NULL),
(1072, '2022-04-05 00:05:46', '2022-04-05 00:05:46', 1, 568, NULL),
(1073, '2022-04-05 00:05:46', '2022-04-05 00:05:46', 2, 568, NULL),
(1074, '2022-04-05 00:14:31', '2022-04-05 00:14:31', 1, 569, NULL),
(1075, '2022-04-05 00:14:31', '2022-04-05 00:14:31', 2, 569, NULL),
(1076, '2022-04-05 00:24:32', '2022-04-05 00:24:32', 1, 570, NULL),
(1077, '2022-04-05 00:24:32', '2022-04-05 00:24:32', 2, 570, NULL),
(1078, '2022-04-05 00:33:25', '2022-04-05 00:33:25', 2, 571, NULL),
(1079, '2022-04-05 00:43:18', '2022-04-05 00:43:18', 1, 572, NULL),
(1080, '2022-04-05 00:43:18', '2022-04-05 00:43:18', 2, 572, NULL),
(1081, '2022-04-05 00:48:16', '2022-04-05 00:48:16', 1, 573, NULL),
(1082, '2022-04-05 00:48:16', '2022-04-05 00:48:16', 2, 573, NULL),
(1083, '2022-04-05 00:56:40', '2022-04-05 00:56:40', 1, 574, NULL),
(1084, '2022-04-05 00:56:40', '2022-04-05 00:56:40', 2, 574, NULL),
(1085, '2022-04-05 01:06:06', '2022-04-05 01:06:06', 1, 575, NULL),
(1086, '2022-04-05 01:06:06', '2022-04-05 01:06:06', 2, 575, NULL),
(1087, '2022-04-05 15:20:52', '2022-04-05 15:20:52', 1, 576, NULL),
(1088, '2022-04-05 15:20:52', '2022-04-05 15:20:52', 2, 576, NULL),
(1089, '2022-04-05 15:24:02', '2022-04-05 15:24:02', 1, 577, NULL),
(1090, '2022-04-05 15:24:02', '2022-04-05 15:24:02', 2, 577, NULL),
(1091, '2022-04-05 15:32:50', '2022-04-05 15:32:50', 1, 578, NULL),
(1092, '2022-04-05 15:43:22', '2022-04-05 15:43:22', 1, 579, NULL),
(1093, '2022-04-05 15:43:22', '2022-04-05 15:43:22', 2, 579, NULL),
(1094, '2022-04-05 15:47:56', '2022-04-05 15:47:56', 1, 580, NULL),
(1095, '2022-04-05 15:47:56', '2022-04-05 15:47:56', 2, 580, NULL),
(1096, '2022-04-05 15:52:18', '2022-04-05 15:52:18', 1, 581, NULL),
(1097, '2022-04-05 15:57:19', '2022-04-05 15:57:19', 1, 582, NULL),
(1098, '2022-04-05 15:57:19', '2022-04-05 15:57:19', 2, 582, NULL),
(1099, '2022-04-05 16:01:55', '2022-04-05 16:01:55', 1, 583, NULL),
(1100, '2022-04-05 16:01:55', '2022-04-05 16:01:55', 2, 583, NULL),
(1101, '2022-04-05 16:07:32', '2022-04-05 16:07:32', 1, 584, NULL),
(1102, '2022-04-05 16:07:32', '2022-04-05 16:07:32', 2, 584, NULL),
(1103, '2022-04-05 16:18:02', '2022-04-05 16:18:02', 1, 585, NULL),
(1104, '2022-04-05 16:25:26', '2022-04-05 16:25:26', 1, 586, NULL),
(1105, '2022-04-05 16:29:17', '2022-04-05 16:29:17', 2, 587, NULL),
(1106, '2022-04-05 17:12:17', '2022-04-05 17:12:17', 1, 588, NULL),
(1107, '2022-04-05 17:12:17', '2022-04-05 17:12:17', 2, 588, NULL),
(1108, '2022-04-05 17:18:43', '2022-04-05 17:18:43', 1, 589, NULL),
(1109, '2022-04-05 17:18:43', '2022-04-05 17:18:43', 2, 589, NULL),
(1110, '2022-04-05 17:21:46', '2022-04-05 17:21:46', 1, 590, NULL),
(1111, '2022-04-05 17:21:46', '2022-04-05 17:21:46', 2, 590, NULL),
(1112, '2022-04-05 17:24:38', '2022-04-05 17:24:38', 1, 591, NULL),
(1113, '2022-04-05 17:24:38', '2022-04-05 17:24:38', 2, 591, NULL),
(1114, '2022-04-05 17:27:51', '2022-04-05 17:27:51', 1, 592, NULL),
(1115, '2022-04-05 17:27:51', '2022-04-05 17:27:51', 2, 592, NULL),
(1116, '2022-04-05 17:31:55', '2022-04-05 17:31:55', 2, 593, NULL),
(1117, '2022-04-05 17:36:35', '2022-04-05 17:36:35', 1, 594, NULL),
(1118, '2022-04-05 17:36:35', '2022-04-05 17:36:35', 2, 594, NULL),
(1119, '2022-04-05 17:39:07', '2022-04-05 17:39:07', 1, 595, NULL),
(1120, '2022-04-05 17:39:07', '2022-04-05 17:39:07', 2, 595, NULL),
(1121, '2022-04-05 17:43:32', '2022-04-05 17:43:32', 1, 596, NULL),
(1122, '2022-04-05 17:43:32', '2022-04-05 17:43:32', 2, 596, NULL),
(1123, '2022-04-05 17:47:37', '2022-04-05 17:47:37', 1, 597, NULL),
(1124, '2022-04-05 17:47:37', '2022-04-05 17:47:37', 2, 597, NULL),
(1125, '2022-04-05 17:51:00', '2022-04-05 17:51:00', 1, 598, NULL),
(1126, '2022-04-05 17:51:00', '2022-04-05 17:51:00', 2, 598, NULL),
(1127, '2022-04-05 17:53:56', '2022-04-05 17:53:56', 1, 599, NULL),
(1128, '2022-04-05 17:53:56', '2022-04-05 17:53:56', 2, 599, NULL),
(1129, '2022-04-05 17:58:24', '2022-04-05 17:58:24', 1, 600, NULL),
(1130, '2022-04-05 17:58:24', '2022-04-05 17:58:24', 2, 600, NULL),
(1131, '2022-04-05 18:04:32', '2022-04-05 18:04:32', 1, 601, NULL),
(1132, '2022-04-05 18:04:32', '2022-04-05 18:04:32', 2, 601, NULL),
(1133, '2022-04-05 18:14:51', '2022-04-05 18:14:51', 1, 602, NULL),
(1134, '2022-04-05 18:14:51', '2022-04-05 18:14:51', 2, 602, NULL),
(1135, '2022-04-05 18:18:23', '2022-04-05 18:18:23', 1, 603, NULL),
(1136, '2022-04-05 18:18:23', '2022-04-05 18:18:23', 2, 603, NULL),
(1137, '2022-04-05 18:20:59', '2022-04-05 18:20:59', 1, 604, NULL),
(1138, '2022-04-05 18:20:59', '2022-04-05 18:20:59', 2, 604, NULL),
(1139, '2022-04-05 18:25:29', '2022-04-05 18:25:29', 1, 605, NULL),
(1140, '2022-04-05 18:25:29', '2022-04-05 18:25:29', 2, 605, NULL),
(1141, '2022-04-05 18:32:07', '2022-04-05 18:32:07', 1, 606, NULL),
(1142, '2022-04-05 18:38:01', '2022-04-05 18:38:01', 1, 607, NULL),
(1143, '2022-04-05 18:38:01', '2022-04-05 18:38:01', 2, 607, NULL),
(1144, '2022-04-05 18:42:47', '2022-04-05 18:42:47', 1, 608, NULL),
(1145, '2022-04-05 18:42:47', '2022-04-05 18:42:47', 2, 608, NULL),
(1146, '2022-04-05 18:50:09', '2022-04-05 18:50:09', 1, 609, NULL),
(1147, '2022-04-05 18:50:09', '2022-04-05 18:50:09', 2, 609, NULL),
(1148, '2022-04-05 18:52:46', '2022-04-05 18:52:46', 1, 610, NULL),
(1149, '2022-04-05 18:52:46', '2022-04-05 18:52:46', 2, 610, NULL),
(1150, '2022-04-05 18:55:49', '2022-04-05 18:55:49', 2, 611, NULL),
(1151, '2022-04-05 18:58:54', '2022-04-05 18:58:54', 1, 612, NULL),
(1152, '2022-04-05 18:58:54', '2022-04-05 18:58:54', 2, 612, NULL),
(1153, '2022-04-05 19:04:07', '2022-04-05 19:04:07', 2, 613, NULL),
(1154, '2022-04-05 19:11:24', '2022-04-05 19:11:24', 1, 614, NULL),
(1155, '2022-04-05 19:11:24', '2022-04-05 19:11:24', 2, 614, NULL),
(1156, '2022-04-05 19:14:52', '2022-04-05 19:14:52', 1, 615, NULL),
(1157, '2022-04-05 19:14:52', '2022-04-05 19:14:52', 2, 615, NULL),
(1158, '2022-04-05 19:21:37', '2022-04-05 19:21:37', 1, 616, NULL),
(1159, '2022-04-05 19:21:37', '2022-04-05 19:21:37', 2, 616, NULL),
(1160, '2022-04-05 19:24:39', '2022-04-05 19:24:39', 1, 617, NULL),
(1161, '2022-04-05 19:24:39', '2022-04-05 19:24:39', 2, 617, NULL),
(1162, '2022-04-05 19:41:28', '2022-04-05 19:41:28', 1, 618, NULL),
(1163, '2022-04-05 19:41:28', '2022-04-05 19:41:28', 2, 618, NULL),
(1164, '2022-04-05 20:11:58', '2022-04-05 20:11:58', 2, 619, NULL),
(1165, '2022-04-05 20:16:21', '2022-04-05 20:16:21', 2, 620, NULL),
(1166, '2022-04-05 20:19:59', '2022-04-05 20:19:59', 2, 621, NULL),
(1167, '2022-04-05 20:23:01', '2022-04-05 20:23:01', 1, 622, NULL),
(1168, '2022-04-05 20:23:01', '2022-04-05 20:23:01', 2, 622, NULL),
(1169, '2022-04-05 20:25:50', '2022-04-05 20:25:50', 2, 623, NULL),
(1170, '2022-04-05 20:53:31', '2022-04-05 20:53:31', 2, 624, NULL),
(1171, '2022-04-05 20:58:04', '2022-04-05 20:58:04', 2, 625, NULL),
(1172, '2022-04-05 21:01:09', '2022-04-05 21:01:09', 2, 626, NULL),
(1173, '2022-04-05 21:06:07', '2022-04-05 21:06:07', 2, 627, NULL),
(1174, '2022-04-05 21:09:12', '2022-04-05 21:09:12', 2, 628, NULL),
(1175, '2022-04-05 21:12:48', '2022-04-05 21:12:48', 1, 629, NULL),
(1176, '2022-04-05 21:12:48', '2022-04-05 21:12:48', 2, 629, NULL),
(1177, '2022-04-05 21:16:46', '2022-04-05 21:16:46', 2, 630, NULL),
(1178, '2022-04-05 21:22:03', '2022-04-05 21:22:03', 2, 631, NULL),
(1179, '2022-04-05 21:27:53', '2022-04-05 21:27:53', 2, 632, NULL),
(1180, '2022-04-05 21:32:52', '2022-04-05 21:32:52', 2, 633, NULL),
(1181, '2022-04-05 21:35:27', '2022-04-05 21:35:27', 2, 634, NULL),
(1182, '2022-04-05 21:38:40', '2022-04-05 21:38:40', 2, 635, NULL),
(1183, '2022-04-05 21:42:07', '2022-04-05 21:42:07', 2, 636, NULL),
(1184, '2022-04-05 21:45:24', '2022-04-05 21:45:24', 2, 637, NULL),
(1185, '2022-04-05 21:51:50', '2022-04-05 21:51:50', 2, 638, NULL),
(1186, '2022-04-05 21:58:02', '2022-04-05 21:58:02', 2, 639, NULL),
(1187, '2022-04-05 22:02:11', '2022-04-05 22:02:11', 2, 640, NULL),
(1188, '2022-04-05 22:06:09', '2022-04-05 22:06:09', 2, 641, NULL),
(1189, '2022-04-05 22:09:05', '2022-04-05 22:09:05', 2, 642, NULL),
(1190, '2022-04-05 22:15:39', '2022-04-05 22:15:39', 2, 643, NULL),
(1191, '2022-04-05 22:19:11', '2022-04-05 22:19:11', 1, 644, NULL),
(1192, '2022-04-05 22:23:28', '2022-04-05 22:23:28', 1, 645, NULL),
(1193, '2022-04-05 22:23:28', '2022-04-05 22:23:28', 2, 645, NULL),
(1194, '2022-04-05 22:26:13', '2022-04-05 22:26:13', 2, 646, NULL),
(1195, '2022-04-05 22:29:03', '2022-04-05 22:29:03', 2, 647, NULL),
(1196, '2022-04-05 22:34:15', '2022-04-05 22:34:15', 1, 648, NULL),
(1197, '2022-04-05 22:34:15', '2022-04-05 22:34:15', 2, 648, NULL),
(1198, '2022-04-05 22:55:02', '2022-04-05 22:55:02', 2, 649, NULL),
(1199, '2022-04-05 22:57:46', '2022-04-05 22:57:46', 2, 650, NULL),
(1200, '2022-04-05 23:00:41', '2022-04-05 23:00:41', 2, 651, NULL),
(1201, '2022-04-05 23:05:20', '2022-04-05 23:05:20', 2, 652, NULL),
(1202, '2022-04-05 23:08:01', '2022-04-05 23:08:01', 2, 653, NULL),
(1203, '2022-04-05 23:13:57', '2022-04-05 23:13:57', 1, 654, NULL),
(1204, '2022-04-05 23:17:02', '2022-04-05 23:17:02', 2, 655, NULL),
(1205, '2022-04-05 23:19:19', '2022-04-05 23:19:19', 2, 656, NULL),
(1206, '2022-04-05 23:21:57', '2022-04-05 23:21:57', 2, 657, NULL),
(1207, '2022-04-05 23:24:47', '2022-04-05 23:24:47', 2, 658, NULL),
(1208, '2022-04-05 23:28:35', '2022-04-05 23:28:35', 2, 659, NULL),
(1209, '2022-04-05 23:31:15', '2022-04-05 23:31:15', 2, 660, NULL),
(1210, '2022-04-05 23:34:09', '2022-04-05 23:34:09', 2, 661, NULL),
(1211, '2022-04-05 23:36:38', '2022-04-05 23:36:38', 2, 662, NULL),
(1212, '2022-04-05 23:39:42', '2022-04-05 23:39:42', 2, 663, NULL),
(1213, '2022-04-05 23:42:46', '2022-04-05 23:42:46', 2, 664, NULL),
(1214, '2022-04-05 23:46:06', '2022-04-05 23:46:06', 1, 665, NULL),
(1215, '2022-04-05 23:46:06', '2022-04-05 23:46:06', 2, 665, NULL),
(1216, '2022-04-05 23:49:21', '2022-04-05 23:49:21', 2, 666, NULL),
(1217, '2022-04-05 23:51:59', '2022-04-05 23:51:59', 2, 667, NULL),
(1218, '2022-04-05 23:54:11', '2022-04-05 23:54:11', 2, 668, NULL),
(1219, '2022-04-05 23:56:39', '2022-04-05 23:56:39', 1, 669, NULL),
(1220, '2022-04-05 23:56:39', '2022-04-05 23:56:39', 2, 669, NULL),
(1221, '2022-04-05 23:59:03', '2022-04-05 23:59:03', 2, 670, NULL),
(1222, '2022-04-06 00:05:06', '2022-04-06 00:05:06', 2, 671, NULL),
(1223, '2022-04-06 00:07:25', '2022-04-06 00:07:25', 2, 672, NULL),
(1224, '2022-04-06 00:09:34', '2022-04-06 00:09:34', 2, 673, NULL),
(1225, '2022-04-06 09:47:28', '2022-04-06 09:47:28', 1, 674, NULL),
(1226, '2022-04-06 09:47:28', '2022-04-06 09:47:28', 2, 674, NULL),
(1227, '2022-04-06 10:10:05', '2022-04-06 10:10:05', 1, 675, NULL),
(1228, '2022-04-06 10:10:05', '2022-04-06 10:10:05', 2, 675, NULL),
(1229, '2022-04-06 10:21:38', '2022-04-06 10:21:38', 1, 676, NULL),
(1230, '2022-04-06 10:21:38', '2022-04-06 10:21:38', 2, 676, NULL),
(1231, '2022-04-06 10:24:49', '2022-04-06 10:24:49', 1, 677, NULL),
(1232, '2022-04-06 10:29:06', '2022-04-06 10:29:06', 1, 678, NULL),
(1233, '2022-04-06 10:29:06', '2022-04-06 10:29:06', 2, 678, NULL),
(1234, '2022-04-06 10:32:28', '2022-04-06 10:32:28', 1, 679, NULL),
(1235, '2022-04-06 10:37:54', '2022-04-06 10:37:54', 1, 680, NULL),
(1236, '2022-04-06 10:37:54', '2022-04-06 10:37:54', 2, 680, NULL),
(1237, '2022-04-06 10:44:18', '2022-04-06 10:44:18', 1, 681, NULL),
(1238, '2022-04-06 10:44:18', '2022-04-06 10:44:18', 2, 681, NULL),
(1239, '2022-04-06 10:49:19', '2022-04-06 10:49:19', 1, 682, NULL),
(1240, '2022-04-06 10:49:19', '2022-04-06 10:49:19', 2, 682, NULL),
(1241, '2022-04-06 10:53:14', '2022-04-06 10:53:14', 1, 683, NULL),
(1242, '2022-04-06 10:53:14', '2022-04-06 10:53:14', 2, 683, NULL),
(1243, '2022-04-06 11:00:36', '2022-04-06 11:00:36', 1, 684, NULL),
(1244, '2022-04-06 11:08:50', '2022-04-06 11:08:50', 1, 685, NULL),
(1245, '2022-04-06 11:08:50', '2022-04-06 11:08:50', 2, 685, NULL),
(1246, '2022-04-06 11:14:18', '2022-04-06 11:14:18', 1, 686, NULL),
(1247, '2022-04-06 11:21:33', '2022-04-06 11:21:33', 1, 687, NULL),
(1248, '2022-04-06 11:25:06', '2022-04-06 11:25:06', 1, 688, NULL),
(1249, '2022-04-06 11:25:06', '2022-04-06 11:25:06', 2, 688, NULL),
(1250, '2022-04-06 11:27:43', '2022-04-06 11:27:43', 1, 689, NULL),
(1251, '2022-04-06 11:27:43', '2022-04-06 11:27:43', 2, 689, NULL),
(1252, '2022-04-06 11:30:19', '2022-04-06 11:30:19', 1, 690, NULL),
(1253, '2022-04-06 11:38:34', '2022-04-06 11:38:34', 1, 691, NULL),
(1254, '2022-04-06 11:38:34', '2022-04-06 11:38:34', 2, 691, NULL),
(1255, '2022-04-06 11:41:31', '2022-04-06 11:41:31', 1, 692, NULL),
(1256, '2022-04-06 11:41:31', '2022-04-06 11:41:31', 2, 692, NULL),
(1257, '2022-04-06 11:47:59', '2022-04-06 11:47:59', 1, 693, NULL),
(1258, '2022-04-06 11:54:20', '2022-04-06 11:54:20', 1, 694, NULL),
(1259, '2022-04-06 11:54:20', '2022-04-06 11:54:20', 2, 694, NULL),
(1260, '2022-04-06 11:58:45', '2022-04-06 11:58:45', 1, 695, NULL),
(1261, '2022-04-06 11:58:45', '2022-04-06 11:58:45', 2, 695, NULL),
(1262, '2022-04-06 12:01:30', '2022-04-06 12:01:30', 1, 696, NULL),
(1263, '2022-04-06 12:13:19', '2022-04-06 12:13:19', 1, 697, NULL),
(1264, '2022-04-06 12:22:11', '2022-04-06 12:22:11', 1, 698, NULL),
(1265, '2022-04-06 12:39:24', '2022-04-06 12:39:24', 1, 699, NULL),
(1266, '2022-04-06 12:44:16', '2022-04-06 12:44:16', 1, 700, NULL),
(1267, '2022-04-06 12:44:16', '2022-04-06 12:44:16', 2, 700, NULL),
(1268, '2022-04-06 12:46:36', '2022-04-06 12:46:36', 1, 701, NULL),
(1269, '2022-04-06 12:46:36', '2022-04-06 12:46:36', 2, 701, NULL),
(1270, '2022-04-06 13:03:33', '2022-04-06 13:03:33', 1, 702, NULL),
(1271, '2022-04-06 13:03:33', '2022-04-06 13:03:33', 2, 702, NULL),
(1272, '2022-04-06 13:18:07', '2022-04-06 13:18:07', 1, 703, NULL),
(1273, '2022-04-06 13:18:07', '2022-04-06 13:18:07', 2, 703, NULL),
(1274, '2022-04-06 13:36:35', '2022-04-06 13:36:35', 2, 704, NULL),
(1275, '2022-04-06 13:42:26', '2022-04-06 13:42:26', 2, 705, NULL),
(1276, '2022-04-06 13:46:53', '2022-04-06 13:46:53', 1, 706, NULL),
(1277, '2022-04-06 13:46:53', '2022-04-06 13:46:53', 2, 706, NULL),
(1278, '2022-04-06 14:26:38', '2022-04-06 14:26:38', 1, 707, NULL),
(1279, '2022-04-06 14:26:38', '2022-04-06 14:26:38', 2, 707, NULL),
(1280, '2022-04-06 14:30:51', '2022-04-06 14:30:51', 1, 708, NULL),
(1281, '2022-04-06 14:30:51', '2022-04-06 14:30:51', 2, 708, NULL),
(1282, '2022-04-06 14:33:12', '2022-04-06 14:33:12', 2, 709, NULL),
(1283, '2022-04-06 14:37:55', '2022-04-06 14:37:55', 1, 710, NULL),
(1284, '2022-04-06 14:37:55', '2022-04-06 14:37:55', 2, 710, NULL),
(1285, '2022-04-06 14:40:27', '2022-04-06 14:40:27', 1, 711, NULL),
(1286, '2022-04-06 14:40:27', '2022-04-06 14:40:27', 2, 711, NULL),
(1287, '2022-04-06 14:43:18', '2022-04-06 14:43:18', 1, 712, NULL),
(1288, '2022-04-06 14:46:36', '2022-04-06 14:46:36', 1, 713, NULL),
(1289, '2022-04-06 14:55:24', '2022-04-06 14:55:24', 1, 714, NULL),
(1290, '2022-04-06 14:55:24', '2022-04-06 14:55:24', 2, 714, NULL),
(1291, '2022-04-06 14:58:43', '2022-04-06 14:58:43', 1, 715, NULL),
(1292, '2022-04-06 14:58:43', '2022-04-06 14:58:43', 2, 715, NULL),
(1293, '2022-04-06 15:01:04', '2022-04-06 15:01:04', 1, 716, NULL),
(1294, '2022-04-06 15:01:04', '2022-04-06 15:01:04', 2, 716, NULL),
(1295, '2022-04-06 15:04:26', '2022-04-06 15:04:26', 1, 717, NULL),
(1296, '2022-04-06 15:04:26', '2022-04-06 15:04:26', 2, 717, NULL),
(1297, '2022-04-06 15:08:23', '2022-04-06 15:08:23', 1, 718, NULL),
(1298, '2022-04-06 15:08:23', '2022-04-06 15:08:23', 2, 718, NULL),
(1299, '2022-04-06 15:13:17', '2022-04-06 15:13:17', 1, 719, NULL),
(1300, '2022-04-06 15:13:17', '2022-04-06 15:13:17', 2, 719, NULL),
(1301, '2022-04-06 15:16:42', '2022-04-06 15:16:42', 1, 720, NULL),
(1302, '2022-04-06 15:16:42', '2022-04-06 15:16:42', 2, 720, NULL),
(1303, '2022-04-06 15:19:44', '2022-04-06 15:19:44', 1, 721, NULL),
(1304, '2022-04-06 15:19:44', '2022-04-06 15:19:44', 2, 721, NULL),
(1305, '2022-04-06 15:22:02', '2022-04-06 15:22:02', 1, 722, NULL),
(1306, '2022-04-06 15:22:02', '2022-04-06 15:22:02', 2, 722, NULL),
(1307, '2022-04-06 15:26:41', '2022-04-06 15:26:41', 1, 723, NULL),
(1308, '2022-04-06 15:26:41', '2022-04-06 15:26:41', 2, 723, NULL),
(1309, '2022-04-06 15:29:35', '2022-04-06 15:29:35', 1, 724, NULL),
(1310, '2022-04-06 15:29:35', '2022-04-06 15:29:35', 2, 724, NULL),
(1311, '2022-04-06 15:33:10', '2022-04-06 15:33:10', 1, 725, NULL),
(1312, '2022-04-06 15:35:44', '2022-04-06 15:35:44', 2, 726, NULL),
(1313, '2022-04-06 15:39:12', '2022-04-06 15:39:12', 1, 727, NULL),
(1314, '2022-04-06 15:39:12', '2022-04-06 15:39:12', 2, 727, NULL),
(1315, '2022-04-06 15:45:08', '2022-04-06 15:45:08', 2, 728, NULL),
(1316, '2022-04-06 15:50:27', '2022-04-06 15:50:27', 1, 729, NULL),
(1317, '2022-04-06 15:50:27', '2022-04-06 15:50:27', 2, 729, NULL),
(1318, '2022-04-06 15:54:49', '2022-04-06 15:54:49', 1, 730, NULL),
(1319, '2022-04-06 15:57:39', '2022-04-06 15:57:39', 2, 731, NULL),
(1320, '2022-04-06 16:05:53', '2022-04-06 16:05:53', 1, 732, NULL),
(1321, '2022-04-06 16:05:53', '2022-04-06 16:05:53', 2, 732, NULL),
(1322, '2022-04-06 16:16:25', '2022-04-06 16:16:25', 1, 733, NULL),
(1323, '2022-04-06 16:29:33', '2022-04-06 16:29:33', 1, 734, NULL),
(1324, '2022-04-06 16:32:39', '2022-04-06 16:32:39', 1, 735, NULL),
(1325, '2022-04-06 16:35:54', '2022-04-06 16:35:54', 1, 736, NULL),
(1326, '2022-04-06 16:40:34', '2022-04-06 16:40:34', 1, 737, NULL),
(1327, '2022-04-06 16:52:32', '2022-04-06 16:52:32', 1, 738, NULL),
(1328, '2022-04-06 16:54:54', '2022-04-06 16:54:54', 1, 739, NULL),
(1329, '2022-04-06 16:59:11', '2022-04-06 16:59:11', 1, 740, NULL),
(1330, '2022-04-06 17:01:37', '2022-04-06 17:01:37', 1, 741, NULL),
(1331, '2022-04-06 17:04:24', '2022-04-06 17:04:24', 1, 742, NULL),
(1332, '2022-04-06 17:06:35', '2022-04-06 17:06:35', 1, 743, NULL),
(1333, '2022-04-06 17:08:53', '2022-04-06 17:08:53', 1, 744, NULL),
(1334, '2022-04-06 17:11:11', '2022-04-06 17:11:11', 1, 745, NULL),
(1335, '2022-04-06 17:16:02', '2022-04-06 17:16:02', 1, 746, NULL),
(1336, '2022-04-06 17:16:02', '2022-04-06 17:16:02', 2, 746, NULL),
(1337, '2022-04-06 17:18:52', '2022-04-06 17:18:52', 1, 747, NULL),
(1338, '2022-04-06 17:18:52', '2022-04-06 17:18:52', 2, 747, NULL),
(1339, '2022-04-06 17:22:32', '2022-04-06 17:22:32', 1, 748, NULL),
(1340, '2022-04-06 17:22:32', '2022-04-06 17:22:32', 2, 748, NULL),
(1341, '2022-04-06 17:31:05', '2022-04-06 17:31:05', 1, 749, NULL),
(1342, '2022-04-06 17:31:05', '2022-04-06 17:31:05', 2, 749, NULL),
(1343, '2022-04-06 17:33:27', '2022-04-06 17:33:27', 1, 750, NULL),
(1344, '2022-04-06 17:33:27', '2022-04-06 17:33:27', 2, 750, NULL),
(1345, '2022-04-06 17:35:23', '2022-04-06 17:35:23', 1, 751, NULL),
(1346, '2022-04-06 17:35:23', '2022-04-06 17:35:23', 2, 751, NULL),
(1347, '2022-04-06 17:45:18', '2022-04-06 17:45:18', 1, 752, NULL),
(1348, '2022-04-06 17:45:18', '2022-04-06 17:45:18', 2, 752, NULL),
(1349, '2022-04-06 17:56:23', '2022-04-06 17:56:23', 1, 753, NULL),
(1350, '2022-04-06 17:56:23', '2022-04-06 17:56:23', 2, 753, NULL),
(1351, '2022-04-06 18:00:38', '2022-04-06 18:00:38', 1, 754, NULL),
(1352, '2022-04-06 18:00:38', '2022-04-06 18:00:38', 2, 754, NULL),
(1353, '2022-04-06 18:04:20', '2022-04-06 18:04:20', 1, 755, NULL),
(1354, '2022-04-06 18:04:20', '2022-04-06 18:04:20', 2, 755, NULL),
(1355, '2022-04-06 18:07:42', '2022-04-06 18:07:42', 1, 756, NULL),
(1356, '2022-04-06 18:07:42', '2022-04-06 18:07:42', 2, 756, NULL),
(1357, '2022-04-06 18:11:23', '2022-04-06 18:11:23', 1, 757, NULL),
(1358, '2022-04-06 18:11:23', '2022-04-06 18:11:23', 2, 757, NULL),
(1359, '2022-04-07 10:40:43', '2022-04-07 10:40:43', 1, 758, NULL),
(1360, '2022-04-07 10:40:43', '2022-04-07 10:40:43', 2, 758, NULL),
(1361, '2022-04-07 10:44:32', '2022-04-07 10:44:32', 1, 759, NULL),
(1362, '2022-04-07 10:44:32', '2022-04-07 10:44:32', 2, 759, NULL),
(1363, '2022-04-07 10:46:35', '2022-04-07 10:46:35', 1, 760, NULL),
(1364, '2022-04-07 10:46:35', '2022-04-07 10:46:35', 2, 760, NULL),
(1365, '2022-04-07 10:49:03', '2022-04-07 10:49:03', 1, 761, NULL),
(1366, '2022-04-07 10:49:03', '2022-04-07 10:49:03', 2, 761, NULL),
(1367, '2022-04-07 10:54:52', '2022-04-07 10:54:52', 1, 762, NULL),
(1368, '2022-04-07 10:54:52', '2022-04-07 10:54:52', 2, 762, NULL),
(1369, '2022-04-07 10:57:11', '2022-04-07 10:57:11', 1, 763, NULL),
(1370, '2022-04-07 10:57:11', '2022-04-07 10:57:11', 2, 763, NULL),
(1371, '2022-04-07 10:58:51', '2022-04-07 10:58:51', 1, 764, NULL),
(1372, '2022-04-07 10:58:51', '2022-04-07 10:58:51', 2, 764, NULL),
(1373, '2022-04-07 11:01:20', '2022-04-07 11:01:20', 1, 765, NULL),
(1374, '2022-04-07 11:01:20', '2022-04-07 11:01:20', 2, 765, NULL),
(1375, '2022-04-07 11:44:16', '2022-04-07 11:44:16', 1, 766, NULL),
(1376, '2022-04-07 11:44:16', '2022-04-07 11:44:16', 2, 766, NULL),
(1377, '2022-04-07 11:47:42', '2022-04-07 11:47:42', 1, 767, NULL),
(1378, '2022-04-07 11:47:42', '2022-04-07 11:47:42', 2, 767, NULL),
(1379, '2022-04-07 14:36:16', '2022-04-07 14:36:16', 1, 768, NULL),
(1380, '2022-04-07 14:36:16', '2022-04-07 14:36:16', 2, 768, NULL),
(1381, '2022-04-22 17:32:29', '2022-04-22 17:32:29', 1, 769, NULL),
(1382, '2022-04-22 17:32:29', '2022-04-22 17:32:29', 2, 769, NULL),
(1383, '2022-04-22 17:49:15', '2022-04-22 17:49:15', 1, 770, NULL),
(1384, '2022-04-22 17:49:15', '2022-04-22 17:49:15', 2, 770, NULL),
(1385, '2022-04-22 18:05:58', '2022-04-22 18:05:58', 1, 771, NULL),
(1386, '2022-04-22 18:05:58', '2022-04-22 18:05:58', 2, 771, NULL),
(1387, '2022-04-22 18:32:31', '2022-04-22 18:32:31', 1, 772, NULL),
(1388, '2022-04-22 18:32:31', '2022-04-22 18:32:31', 2, 772, NULL),
(1389, '2022-04-22 18:39:22', '2022-04-22 18:39:22', 1, 773, NULL),
(1390, '2022-04-22 18:39:22', '2022-04-22 18:39:22', 2, 773, NULL),
(1391, '2022-04-22 18:59:23', '2022-04-22 18:59:23', 1, 774, NULL),
(1392, '2022-04-22 18:59:23', '2022-04-22 18:59:23', 2, 774, NULL),
(1393, '2022-04-22 19:04:15', '2022-04-22 19:04:15', 1, 775, NULL),
(1394, '2022-04-22 19:04:15', '2022-04-22 19:04:15', 2, 775, NULL),
(1395, '2022-04-22 19:07:11', '2022-04-22 19:07:11', 1, 776, NULL),
(1396, '2022-04-22 19:07:11', '2022-04-22 19:07:11', 2, 776, NULL),
(1397, '2022-04-22 19:10:43', '2022-04-22 19:10:43', 1, 777, NULL),
(1398, '2022-04-25 12:34:57', '2022-04-25 12:34:57', 1, 778, NULL),
(1399, '2022-04-25 12:34:57', '2022-04-25 12:34:57', 2, 778, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TIPO_PREDIO`
--

CREATE TABLE `TIPO_PREDIO` (
  `TIP_CODIGO` int NOT NULL,
  `TIP_NOMBRE` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `TIP_CREATED` datetime NOT NULL,
  `TIP_UPDATED` datetime NOT NULL,
  `TIP_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `TIPO_PREDIO`
--

INSERT INTO `TIPO_PREDIO` (`TIP_CODIGO`, `TIP_NOMBRE`, `TIP_CREATED`, `TIP_UPDATED`, `TIP_DELETED`) VALUES
(1, 'SOCIAL', '2022-02-10 00:00:00', '2022-03-27 20:19:21', NULL),
(2, 'INDUSTRIAL', '2022-02-23 19:12:47', '2022-03-21 20:48:53', NULL),
(3, 'CASA/HABITACÓN', '2022-02-24 10:40:06', '2022-03-21 12:57:00', '2022-03-21 12:57:00'),
(4, 'CASA/HABITACIÓN', '2022-03-21 12:32:24', '2022-03-21 12:37:58', '2022-03-21 12:37:58'),
(5, 'CASA/HABITACIÓN', '2022-03-21 12:39:12', '2022-03-21 12:56:10', '2022-03-21 12:56:10'),
(6, 'CA SA/HABITACIÓN', '2022-03-21 13:49:05', '2022-03-21 13:50:01', '2022-03-21 13:50:01'),
(7, 'CASA /HABITACIÓN', '2022-03-21 14:08:14', '2022-03-21 15:47:48', '2022-03-21 15:47:48'),
(8, 'CASA  /HABITACIÓN', '2022-03-21 14:30:36', '2022-03-21 15:47:58', '2022-03-21 15:47:58'),
(9, 'COMERCIAL.', '2022-03-21 14:53:07', '2022-03-21 15:48:07', '2022-03-21 15:48:07'),
(10, 'CA SA/HABITACIÓN', '2022-03-21 15:01:42', '2022-03-21 15:41:05', '2022-03-21 15:41:05'),
(11, 'DOMESTICO', '2022-03-21 20:42:34', '2022-03-21 20:50:00', NULL),
(12, 'ESTATAL', '2022-03-21 22:43:11', '2022-03-21 22:43:11', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TIPO_USO_PREDIO`
--

CREATE TABLE `TIPO_USO_PREDIO` (
  `TUP_CODIGO` int NOT NULL,
  `TUP_NOMBRE` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `TUP_TARIFA_AGUA` double(5,2) NOT NULL,
  `TUP_TARIFA_DESAGUE` double(5,2) NOT NULL,
  `TUP_TARIFA_AMBOS` double(7,2) NOT NULL,
  `TUP_TARIFA_MANTENIMIENTO` double(7,2) NOT NULL,
  `TUP_CREATED` datetime NOT NULL,
  `TUP_UPDATED` datetime NOT NULL,
  `TIP_CODIGO` int NOT NULL,
  `TUP_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `TIPO_USO_PREDIO`
--

INSERT INTO `TIPO_USO_PREDIO` (`TUP_CODIGO`, `TUP_NOMBRE`, `TUP_TARIFA_AGUA`, `TUP_TARIFA_DESAGUE`, `TUP_TARIFA_AMBOS`, `TUP_TARIFA_MANTENIMIENTO`, `TUP_CREATED`, `TUP_UPDATED`, `TIP_CODIGO`, `TUP_DELETED`) VALUES
(1, 'CASA/HABITACIÓN . AGUA - DESAGUE', 10.00, 12.00, 18.00, 5.00, '2022-02-10 00:00:00', '2022-04-09 16:01:16', 1, NULL),
(2, 'CASA/HABITACIÓN 2', 12.00, 12.00, 0.00, 0.00, '2022-02-24 10:42:59', '2022-03-27 19:31:35', 1, '2022-03-27 19:31:35'),
(3, 'CA SA/HABITACÓN', 18.00, 18.00, 0.00, 0.00, '2022-03-21 12:27:44', '2022-03-21 12:41:37', 2, '2022-03-21 12:41:37'),
(4, 'CASA /HABITACIÓN', 18.00, 18.00, 0.00, 0.00, '2022-03-21 14:11:57', '2022-03-21 15:44:57', 1, '2022-03-21 15:44:57'),
(5, 'CASA  /HABITACIÓN', 18.00, 18.00, 0.00, 0.00, '2022-03-21 14:35:54', '2022-03-21 15:45:23', 1, '2022-03-21 15:45:23'),
(6, 'COMERCIAL.', 120.00, 120.00, 0.00, 0.00, '2022-03-21 14:56:36', '2022-03-21 15:45:33', 9, '2022-03-21 15:45:33'),
(7, 'CASA /HABITACIÓN', 18.00, 18.00, 0.00, 0.00, '2022-03-21 15:52:59', '2022-03-21 15:53:11', 1, '2022-03-21 15:53:11'),
(8, 'COMERCIAL.', 200.00, 200.00, 0.00, 0.00, '2022-03-21 15:53:45', '2022-03-21 15:53:51', 2, '2022-03-21 15:53:51'),
(9, 'CA SA/HABITACIÓN', 18.00, 18.00, 0.00, 0.00, '2022-03-21 15:54:39', '2022-03-21 15:54:57', 1, '2022-03-21 15:54:57'),
(10, 'CASA  /HABITACIÓN', 120.00, 120.00, 0.00, 0.00, '2022-03-21 15:55:24', '2022-03-21 15:55:33', 1, '2022-03-21 15:55:33'),
(11, 'CAMERCIAL 2', 120.00, 120.00, 0.00, 0.00, '2022-03-21 21:06:21', '2022-03-22 11:26:29', 2, '2022-03-22 11:26:29'),
(12, 'CASA/HABITACIÓN 2', 12.00, 12.00, 0.00, 0.00, '2022-03-22 00:36:26', '2022-03-22 11:27:14', 1, '2022-03-22 11:27:14'),
(13, 'CASA', 25.00, 25.00, 0.00, 0.00, '2022-03-22 00:38:21', '2022-03-22 11:27:08', 1, '2022-03-22 11:27:08'),
(14, 'CASA/HABITACIÓN 3', 10.00, 10.00, 0.00, 0.00, '2022-03-22 00:41:17', '2022-03-22 11:27:00', 1, '2022-03-22 11:27:00'),
(15, 'QUINTA', 25.00, 25.00, 0.00, 0.00, '2022-03-22 00:47:25', '2022-03-22 11:26:53', 11, '2022-03-22 11:26:53'),
(16, 'CASA/QUINTA', 18.00, 18.00, 0.00, 0.00, '2022-03-22 01:12:03', '2022-03-22 11:26:44', 1, '2022-03-22 11:26:44'),
(17, 'HABITACIÓN 1', 12.00, 0.00, 0.00, 0.00, '2022-03-22 01:36:47', '2022-03-22 11:26:36', 1, '2022-03-22 11:26:36'),
(18, 'CASA/HABITACIÓN 3', 10.00, 10.00, 0.00, 0.00, '2022-03-22 11:29:27', '2022-03-27 19:26:10', 1, '2022-03-27 19:26:10'),
(19, 'CASA/HABITACIÓN 4', 5.00, 5.00, 0.00, 0.00, '2022-03-22 11:30:35', '2022-03-27 19:30:58', 1, '2022-03-27 19:30:58'),
(20, 'CASA/HABITACIÓN 5', 30.00, 30.00, 0.00, 0.00, '2022-03-22 11:31:14', '2022-03-27 19:30:48', 1, '2022-03-27 19:30:48'),
(21, 'CASA/HABITACIÓN 6', 25.00, 25.00, 0.00, 0.00, '2022-03-22 11:36:40', '2022-03-27 19:30:40', 1, '2022-03-27 19:30:40'),
(22, 'CASA/HABITACIÓN 7', 10.00, 0.00, 0.00, 0.00, '2022-03-22 11:43:11', '2022-03-27 19:30:30', 1, '2022-03-27 19:30:30'),
(23, 'CASA/HABITACIÓN 8', 18.00, 0.00, 0.00, 0.00, '2022-03-22 11:43:57', '2022-03-27 19:30:23', 1, '2022-03-27 19:30:23'),
(24, 'CASA/HABITACIÓN 9', 12.00, 0.00, 0.00, 0.00, '2022-03-22 11:48:22', '2022-03-27 19:30:12', 1, '2022-03-27 19:30:12'),
(25, 'CASA/HABITACIÓN 10', 0.00, 12.00, 0.00, 0.00, '2022-03-22 11:49:09', '2022-03-27 19:30:01', 1, '2022-03-27 19:30:01'),
(26, 'COMERCIAL 1', 200.00, 200.00, 0.00, 0.00, '2022-03-22 11:50:12', '2022-03-27 19:29:52', 2, '2022-03-27 19:29:52'),
(27, 'COMERCIAL 2', 120.00, 120.00, 0.00, 0.00, '2022-03-22 12:12:30', '2022-03-27 19:29:40', 2, '2022-03-27 19:29:40'),
(28, 'COMERCIAL 3', 250.00, 250.00, 0.00, 0.00, '2022-03-22 12:13:20', '2022-03-27 19:29:31', 2, '2022-03-27 19:29:31'),
(29, 'COMERCIAL 4', 60.00, 60.00, 0.00, 0.00, '2022-03-22 14:45:22', '2022-03-27 19:29:23', 2, '2022-03-27 19:29:23'),
(30, 'COMERCIAL 5', 50.00, 50.00, 0.00, 0.00, '2022-03-22 14:47:30', '2022-03-27 19:29:13', 2, '2022-03-27 19:29:13'),
(31, 'COMERCIAL 6', 18.00, 18.00, 0.00, 0.00, '2022-03-22 14:48:49', '2022-03-27 19:29:06', 2, '2022-03-27 19:29:06'),
(32, 'COMERCIAL 7', 12.00, 0.00, 0.00, 0.00, '2022-03-22 14:49:34', '2022-03-27 19:28:58', 2, '2022-03-27 19:28:58'),
(33, 'COMERCIAL 8', 0.00, 12.00, 0.00, 0.00, '2022-03-22 14:50:23', '2022-03-27 19:28:50', 2, '2022-03-27 19:28:50'),
(34, 'CASA/HABITACIÓN. DESAGUE', 0.00, 12.00, 0.00, 5.00, '2022-03-22 14:53:26', '2022-04-09 16:00:48', 1, '2022-04-09 16:00:48'),
(35, 'QUINTA/CASA 2', 25.00, 0.00, 0.00, 0.00, '2022-03-22 14:55:54', '2022-03-27 19:26:38', 11, '2022-03-27 19:26:38'),
(36, 'QUINTA/CASA 3', 10.00, 0.00, 0.00, 0.00, '2022-03-22 14:56:20', '2022-03-27 19:26:29', 11, '2022-03-27 19:26:29'),
(37, 'ESTATAL 1', 50.00, 0.00, 0.00, 0.00, '2022-03-22 15:00:49', '2022-03-27 19:26:20', 12, '2022-03-27 19:26:20'),
(38, 'DESHABILITADA', 0.00, 0.00, 0.00, 0.00, '2022-03-22 15:07:36', '2022-03-24 10:30:26', 1, '2022-03-24 10:30:26'),
(39, 'CASA/HABITACIÓN . AGUA', 10.00, 0.00, 0.00, 5.00, '2022-03-27 20:15:28', '2022-04-09 16:01:00', 1, '2022-04-09 16:01:00'),
(40, 'CASA/QUINTA -25.00', 12.50, 12.50, 25.00, 0.00, '2022-03-27 20:16:10', '2022-04-09 15:34:21', 11, NULL),
(41, 'CASA/QUINTA -35.00', 17.50, 17.50, 35.00, 0.00, '2022-03-27 20:22:13', '2022-04-09 15:34:10', 11, NULL),
(42, 'MANTENIMIENTO', 2.50, 2.50, 0.00, 0.00, '2022-03-27 20:23:20', '2022-03-28 16:42:17', 1, '2022-03-28 16:42:17'),
(43, 'ESTATAL/94.40', 47.20, 47.20, 94.40, 0.00, '2022-03-27 20:24:45', '2022-04-09 15:34:00', 12, NULL),
(44, 'COMERCIAL/250', 125.00, 125.00, 250.00, 0.00, '2022-03-27 20:29:26', '2022-04-09 15:33:48', 2, NULL),
(45, 'COMERCIAL/153.40', 76.70, 76.70, 153.40, 0.00, '2022-03-27 20:30:20', '2022-04-09 15:33:35', 2, NULL),
(46, 'COMERCIAL/120', 60.00, 60.00, 120.00, 0.00, '2022-03-27 20:38:32', '2022-04-03 23:37:53', 2, NULL),
(47, 'COMERCIAL/80', 40.00, 40.00, 80.00, 0.00, '2022-03-27 20:39:10', '2022-04-03 23:38:19', 2, NULL),
(48, 'COMERCIAL 3', 30.00, 30.00, 0.00, 0.00, '2022-03-27 20:39:37', '2022-03-29 04:34:00', 2, '2022-03-29 04:34:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TIPO_USUARIO`
--

CREATE TABLE `TIPO_USUARIO` (
  `TPU_CODIGO` int NOT NULL,
  `TPU_NOMBRE` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `TPU_CREATED` datetime NOT NULL,
  `TPU_UPDATED` datetime NOT NULL,
  `TPU_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `TIPO_USUARIO`
--

INSERT INTO `TIPO_USUARIO` (`TPU_CODIGO`, `TPU_NOMBRE`, `TPU_CREATED`, `TPU_UPDATED`, `TPU_DELETED`) VALUES
(1, 'ADMINISTRADOR', '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL),
(2, 'TESORERO', '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL),
(3, 'COBRANZAS', '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL),
(4, 'AUXILIAR', '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TIPO_USUARIO_CAJA`
--

CREATE TABLE `TIPO_USUARIO_CAJA` (
  `TUC_CODIGO` int NOT NULL,
  `TUC_CREATED` datetime NOT NULL,
  `TUC_UPDATED` datetime NOT NULL,
  `TUC_DELETED` datetime DEFAULT NULL,
  `TPU_CODIGO` int NOT NULL,
  `CAJ_CODIGO` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `TIPO_USUARIO_CAJA`
--

INSERT INTO `TIPO_USUARIO_CAJA` (`TUC_CODIGO`, `TUC_CREATED`, `TUC_UPDATED`, `TUC_DELETED`, `TPU_CODIGO`, `CAJ_CODIGO`) VALUES
(1, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 1, 1),
(2, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 1, 2),
(3, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 1, 3),
(4, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 2, 1),
(5, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 2, 2),
(6, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 2, 3),
(7, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 3, 1),
(8, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 3, 2),
(9, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 4, 1),
(10, '2022-02-10 00:00:00', '2022-02-10 00:00:00', NULL, 4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIO`
--

CREATE TABLE `USUARIO` (
  `USU_CODIGO` int NOT NULL,
  `USU_NOMBRES` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `USU_APELLIDOS` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `USU_USUARIO` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `USU_EMAIL` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `USU_PASSWORD` varchar(260) COLLATE utf8_spanish_ci NOT NULL,
  `USU_ESTADO` bit(1) NOT NULL,
  `USU_INTENTOS_FALLIDOS` int NOT NULL DEFAULT '0',
  `USU_REQUEST_TOKEN` int NOT NULL DEFAULT '0',
  `USU_TOKEN_RECOVERY` varchar(256) COLLATE utf8_spanish_ci DEFAULT NULL,
  `USU_TOKEN_FECHA` datetime DEFAULT NULL,
  `USU_CREATED` datetime NOT NULL,
  `USU_UPDATED` datetime NOT NULL,
  `TPU_CODIGO` int NOT NULL,
  `USU_DELETED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `USUARIO`
--

INSERT INTO `USUARIO` (`USU_CODIGO`, `USU_NOMBRES`, `USU_APELLIDOS`, `USU_USUARIO`, `USU_EMAIL`, `USU_PASSWORD`, `USU_ESTADO`, `USU_INTENTOS_FALLIDOS`, `USU_REQUEST_TOKEN`, `USU_TOKEN_RECOVERY`, `USU_TOKEN_FECHA`, `USU_CREATED`, `USU_UPDATED`, `TPU_CODIGO`, `USU_DELETED`) VALUES
(1, 'TEST', 'JASS', 'testjass', 'deprueba@gmail.com', '$2y$10$Yq/GrSnmY2cDh06pGwAqLOborkFFOTWWdwZ2y3G7z2k4ZeGRVPUOi', b'1', 0, 0, NULL, NULL, '2022-02-10 00:00:00', '2022-06-02 10:42:29', 1, NULL),
(2, 'FLORENCIO', 'MISCAN', 'jasschosicadelnorte', 'jasschosica@gmail.com', '$2y$10$CjGyORVeaJBaXv0TVAZZuuGD9k8yHyOJZDGN6M0SP/Ez33TgWSice', b'1', 0, 0, NULL, NULL, '2022-02-10 00:00:00', '2022-07-01 11:30:28', 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `CAJA`
--
ALTER TABLE `CAJA`
  ADD PRIMARY KEY (`CAJ_CODIGO`);

--
-- Indices de la tabla `CALLE`
--
ALTER TABLE `CALLE`
  ADD PRIMARY KEY (`CAL_CODIGO`);

--
-- Indices de la tabla `CLIENTE`
--
ALTER TABLE `CLIENTE`
  ADD PRIMARY KEY (`CLI_CODIGO`);

--
-- Indices de la tabla `CONTRATO`
--
ALTER TABLE `CONTRATO`
  ADD PRIMARY KEY (`CTO_CODIGO`),
  ADD KEY `CTO_PRE` (`PRE_CODIGO`),
  ADD KEY `CTO_TUP` (`TUP_CODIGO`);

--
-- Indices de la tabla `CUOTA_EXTRAORDINARIA`
--
ALTER TABLE `CUOTA_EXTRAORDINARIA`
  ADD PRIMARY KEY (`CUE_CODIGO`),
  ADD UNIQUE KEY `cu_ingreso` (`IGR_CODIGO`),
  ADD KEY `PTO_CUE` (`PTO_CODIGO`),
  ADD KEY `CUE_CTO` (`CTO_CODIGO`);

--
-- Indices de la tabla `EGRESO`
--
ALTER TABLE `EGRESO`
  ADD PRIMARY KEY (`EGR_CODIGO`),
  ADD KEY `EGR_CAJ` (`CAJ_CODIGO`);

--
-- Indices de la tabla `FINANCIAMIENTO`
--
ALTER TABLE `FINANCIAMIENTO`
  ADD PRIMARY KEY (`FTO_CODIGO`);

--
-- Indices de la tabla `FINANC_CUOTA`
--
ALTER TABLE `FINANC_CUOTA`
  ADD PRIMARY KEY (`FCU_CODIGO`),
  ADD KEY `FCU_FTO` (`FTO_CODIGO`);

--
-- Indices de la tabla `IGV`
--
ALTER TABLE `IGV`
  ADD PRIMARY KEY (`IGV_CODIGO`);

--
-- Indices de la tabla `INGRESO`
--
ALTER TABLE `INGRESO`
  ADD PRIMARY KEY (`IGR_CODIGO`),
  ADD KEY `IGR_CAJ` (`CAJ_CODIGO`);

--
-- Indices de la tabla `PREDIO`
--
ALTER TABLE `PREDIO`
  ADD PRIMARY KEY (`PRE_CODIGO`),
  ADD KEY `CLI_PRE` (`CLI_CODIGO`),
  ADD KEY `CAL_PRE` (`CAL_CODIGO`);

--
-- Indices de la tabla `PROYECTO`
--
ALTER TABLE `PROYECTO`
  ADD PRIMARY KEY (`PTO_CODIGO`);

--
-- Indices de la tabla `RECIBO`
--
ALTER TABLE `RECIBO`
  ADD PRIMARY KEY (`RBO_CODIGO`),
  ADD UNIQUE KEY `cu_ingreso` (`IGR_CODIGO`),
  ADD KEY `RBO_CTO` (`CTO_CODIGO`),
  ADD KEY `RBO_FTO` (`FTO_CODIGO`);

--
-- Indices de la tabla `SECTOR`
--
ALTER TABLE `SECTOR`
  ADD PRIMARY KEY (`STR_CODIGO`);

--
-- Indices de la tabla `SECTOR_CALLE`
--
ALTER TABLE `SECTOR_CALLE`
  ADD PRIMARY KEY (`STC_CODIGO`),
  ADD KEY `STC_STR` (`STR_CODIGO`),
  ADD KEY `STC_CAL` (`CAL_CODIGO`);

--
-- Indices de la tabla `SERVICIO`
--
ALTER TABLE `SERVICIO`
  ADD PRIMARY KEY (`SRV_CODIGO`);

--
-- Indices de la tabla `SERVICIO_ADICIONAL_RBO`
--
ALTER TABLE `SERVICIO_ADICIONAL_RBO`
  ADD PRIMARY KEY (`SAR_CODIGO`),
  ADD KEY `SAR_CTO` (`CTO_CODIGO`);

--
-- Indices de la tabla `SERVICIO_CONTRATO`
--
ALTER TABLE `SERVICIO_CONTRATO`
  ADD PRIMARY KEY (`SRC_CODIGO`),
  ADD KEY `SRC_SRV` (`SRV_CODIGO`),
  ADD KEY `SRC_CTO` (`CTO_CODIGO`);

--
-- Indices de la tabla `TIPO_PREDIO`
--
ALTER TABLE `TIPO_PREDIO`
  ADD PRIMARY KEY (`TIP_CODIGO`);

--
-- Indices de la tabla `TIPO_USO_PREDIO`
--
ALTER TABLE `TIPO_USO_PREDIO`
  ADD PRIMARY KEY (`TUP_CODIGO`),
  ADD KEY `TUP_TIP` (`TIP_CODIGO`);

--
-- Indices de la tabla `TIPO_USUARIO`
--
ALTER TABLE `TIPO_USUARIO`
  ADD PRIMARY KEY (`TPU_CODIGO`);

--
-- Indices de la tabla `TIPO_USUARIO_CAJA`
--
ALTER TABLE `TIPO_USUARIO_CAJA`
  ADD PRIMARY KEY (`TUC_CODIGO`),
  ADD KEY `TUC_TPU` (`TPU_CODIGO`),
  ADD KEY `TUC_CAJ` (`CAJ_CODIGO`);

--
-- Indices de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  ADD PRIMARY KEY (`USU_CODIGO`),
  ADD KEY `TPU_USU` (`TPU_CODIGO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `CAJA`
--
ALTER TABLE `CAJA`
  MODIFY `CAJ_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `CALLE`
--
ALTER TABLE `CALLE`
  MODIFY `CAL_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `CLIENTE`
--
ALTER TABLE `CLIENTE`
  MODIFY `CLI_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=854;

--
-- AUTO_INCREMENT de la tabla `CONTRATO`
--
ALTER TABLE `CONTRATO`
  MODIFY `CTO_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=779;

--
-- AUTO_INCREMENT de la tabla `CUOTA_EXTRAORDINARIA`
--
ALTER TABLE `CUOTA_EXTRAORDINARIA`
  MODIFY `CUE_CODIGO` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EGRESO`
--
ALTER TABLE `EGRESO`
  MODIFY `EGR_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `FINANCIAMIENTO`
--
ALTER TABLE `FINANCIAMIENTO`
  MODIFY `FTO_CODIGO` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `FINANC_CUOTA`
--
ALTER TABLE `FINANC_CUOTA`
  MODIFY `FCU_CODIGO` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `IGV`
--
ALTER TABLE `IGV`
  MODIFY `IGV_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `INGRESO`
--
ALTER TABLE `INGRESO`
  MODIFY `IGR_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `PREDIO`
--
ALTER TABLE `PREDIO`
  MODIFY `PRE_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=779;

--
-- AUTO_INCREMENT de la tabla `PROYECTO`
--
ALTER TABLE `PROYECTO`
  MODIFY `PTO_CODIGO` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RECIBO`
--
ALTER TABLE `RECIBO`
  MODIFY `RBO_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=776;

--
-- AUTO_INCREMENT de la tabla `SECTOR`
--
ALTER TABLE `SECTOR`
  MODIFY `STR_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `SECTOR_CALLE`
--
ALTER TABLE `SECTOR_CALLE`
  MODIFY `STC_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `SERVICIO`
--
ALTER TABLE `SERVICIO`
  MODIFY `SRV_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `SERVICIO_ADICIONAL_RBO`
--
ALTER TABLE `SERVICIO_ADICIONAL_RBO`
  MODIFY `SAR_CODIGO` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `SERVICIO_CONTRATO`
--
ALTER TABLE `SERVICIO_CONTRATO`
  MODIFY `SRC_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1400;

--
-- AUTO_INCREMENT de la tabla `TIPO_PREDIO`
--
ALTER TABLE `TIPO_PREDIO`
  MODIFY `TIP_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `TIPO_USO_PREDIO`
--
ALTER TABLE `TIPO_USO_PREDIO`
  MODIFY `TUP_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `TIPO_USUARIO`
--
ALTER TABLE `TIPO_USUARIO`
  MODIFY `TPU_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `TIPO_USUARIO_CAJA`
--
ALTER TABLE `TIPO_USUARIO_CAJA`
  MODIFY `TUC_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  MODIFY `USU_CODIGO` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `CONTRATO`
--
ALTER TABLE `CONTRATO`
  ADD CONSTRAINT `CONTRATO_ibfk_1` FOREIGN KEY (`PRE_CODIGO`) REFERENCES `PREDIO` (`PRE_CODIGO`),
  ADD CONSTRAINT `CONTRATO_ibfk_2` FOREIGN KEY (`TUP_CODIGO`) REFERENCES `TIPO_USO_PREDIO` (`TUP_CODIGO`);

--
-- Filtros para la tabla `CUOTA_EXTRAORDINARIA`
--
ALTER TABLE `CUOTA_EXTRAORDINARIA`
  ADD CONSTRAINT `CUOTA_EXTRAORDINARIA_ibfk_1` FOREIGN KEY (`PTO_CODIGO`) REFERENCES `PROYECTO` (`PTO_CODIGO`),
  ADD CONSTRAINT `CUOTA_EXTRAORDINARIA_ibfk_2` FOREIGN KEY (`CTO_CODIGO`) REFERENCES `CONTRATO` (`CTO_CODIGO`),
  ADD CONSTRAINT `CUOTA_EXTRAORDINARIA_ibfk_3` FOREIGN KEY (`IGR_CODIGO`) REFERENCES `INGRESO` (`IGR_CODIGO`);

--
-- Filtros para la tabla `EGRESO`
--
ALTER TABLE `EGRESO`
  ADD CONSTRAINT `EGRESO_ibfk_1` FOREIGN KEY (`CAJ_CODIGO`) REFERENCES `CAJA` (`CAJ_CODIGO`);

--
-- Filtros para la tabla `FINANC_CUOTA`
--
ALTER TABLE `FINANC_CUOTA`
  ADD CONSTRAINT `FINANC_CUOTA_ibfk_1` FOREIGN KEY (`FTO_CODIGO`) REFERENCES `FINANCIAMIENTO` (`FTO_CODIGO`);

--
-- Filtros para la tabla `INGRESO`
--
ALTER TABLE `INGRESO`
  ADD CONSTRAINT `INGRESO_ibfk_1` FOREIGN KEY (`CAJ_CODIGO`) REFERENCES `CAJA` (`CAJ_CODIGO`);

--
-- Filtros para la tabla `PREDIO`
--
ALTER TABLE `PREDIO`
  ADD CONSTRAINT `PREDIO_ibfk_1` FOREIGN KEY (`CLI_CODIGO`) REFERENCES `CLIENTE` (`CLI_CODIGO`),
  ADD CONSTRAINT `PREDIO_ibfk_2` FOREIGN KEY (`CAL_CODIGO`) REFERENCES `CALLE` (`CAL_CODIGO`);

--
-- Filtros para la tabla `RECIBO`
--
ALTER TABLE `RECIBO`
  ADD CONSTRAINT `RECIBO_ibfk_1` FOREIGN KEY (`CTO_CODIGO`) REFERENCES `CONTRATO` (`CTO_CODIGO`),
  ADD CONSTRAINT `RECIBO_ibfk_2` FOREIGN KEY (`FTO_CODIGO`) REFERENCES `FINANCIAMIENTO` (`FTO_CODIGO`),
  ADD CONSTRAINT `RECIBO_ibfk_3` FOREIGN KEY (`IGR_CODIGO`) REFERENCES `INGRESO` (`IGR_CODIGO`);

--
-- Filtros para la tabla `SECTOR_CALLE`
--
ALTER TABLE `SECTOR_CALLE`
  ADD CONSTRAINT `SECTOR_CALLE_ibfk_1` FOREIGN KEY (`STR_CODIGO`) REFERENCES `SECTOR` (`STR_CODIGO`),
  ADD CONSTRAINT `SECTOR_CALLE_ibfk_2` FOREIGN KEY (`CAL_CODIGO`) REFERENCES `CALLE` (`CAL_CODIGO`);

--
-- Filtros para la tabla `SERVICIO_ADICIONAL_RBO`
--
ALTER TABLE `SERVICIO_ADICIONAL_RBO`
  ADD CONSTRAINT `SERVICIO_ADICIONAL_RBO_ibfk_1` FOREIGN KEY (`CTO_CODIGO`) REFERENCES `CONTRATO` (`CTO_CODIGO`);

--
-- Filtros para la tabla `SERVICIO_CONTRATO`
--
ALTER TABLE `SERVICIO_CONTRATO`
  ADD CONSTRAINT `SERVICIO_CONTRATO_ibfk_1` FOREIGN KEY (`SRV_CODIGO`) REFERENCES `SERVICIO` (`SRV_CODIGO`),
  ADD CONSTRAINT `SERVICIO_CONTRATO_ibfk_2` FOREIGN KEY (`CTO_CODIGO`) REFERENCES `CONTRATO` (`CTO_CODIGO`);

--
-- Filtros para la tabla `TIPO_USO_PREDIO`
--
ALTER TABLE `TIPO_USO_PREDIO`
  ADD CONSTRAINT `TIPO_USO_PREDIO_ibfk_1` FOREIGN KEY (`TIP_CODIGO`) REFERENCES `TIPO_PREDIO` (`TIP_CODIGO`);

--
-- Filtros para la tabla `TIPO_USUARIO_CAJA`
--
ALTER TABLE `TIPO_USUARIO_CAJA`
  ADD CONSTRAINT `TIPO_USUARIO_CAJA_ibfk_1` FOREIGN KEY (`TPU_CODIGO`) REFERENCES `TIPO_USUARIO` (`TPU_CODIGO`),
  ADD CONSTRAINT `TIPO_USUARIO_CAJA_ibfk_2` FOREIGN KEY (`CAJ_CODIGO`) REFERENCES `CAJA` (`CAJ_CODIGO`);

--
-- Filtros para la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  ADD CONSTRAINT `USUARIO_ibfk_1` FOREIGN KEY (`TPU_CODIGO`) REFERENCES `TIPO_USUARIO` (`TPU_CODIGO`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



-- View 01 : Sustentaciones detallado y pendiente

CREATE  VIEW vxSustens AS
 SELECT S.Id, S.IdTramite, S.Codigo, if((now() > S.Fecha),0,1) AS Pendiente,
        C.Id AS IdCarrera, C.IdFacultad, C.Nombre AS Carrera, S.Fecha, S.Lugar
   FROM tesSustens AS S,
        desarrollo_absmain.dicCarreras AS C
  WHERE C.Id = S.IdCarrera
;



/****/



-- FechaModif para Tramites  obtenido de la ultima iteracion
UPDATE tesTramites SET FechModif = 
  ( SELECT T.Fecha FROM tesTramsDet AS T WHERE T.IdTramite=tesTramites.Id ORDER BY T.Iteracion DESC LIMIT 1 )


-- insertado las correcciones
INSERT INTO `tblCorrects`
(SELECT null, IdTramite, Iteracion, IdDocente, Fecha, Mensaje FROM tblCorrBorr)


-- correcciones
UPDATE tblCorrBorr SET IdTramite=
  (SELECT __tesBorradsNO.IdTramite FROM __tesBorradsNO WHERE __tesBorradsNO.Id=tblCorrBorr.IdBorrador)


-- poner IdTramite a tesSustens
UPDATE tesSustens SET IdTramite=
  (SELECT tesBorrads.IdTramite FROM tesBorrads WHERE tesBorrads.Id=tesSustens.IdBorrador)


-- Buscar en borradores no anexados
SELECT * FROM `tesBorrasDet` WHERE IdBorrador NOT IN (select Id from tesBorrads)

-- Buscar los TramitesDet sin Padre
SELECT * FROM `tesTramsDet` WHERE IdTramite NOT IN (SELECT Id FROM tesTramites)



-- Borrar los que no tengan tramite.

SELECT  Id, IdCarrera, Apellidos,
 ( SELECT * FROM tesTramites WHERE IdTesista1=tblTesistas.Id  OR IdTesista2=tblTesistas.Id ) AS Valido
FROM tblTesistas ORDER BY Id, Valido DESC

UPDATE tblTesistas SET  Tiene = 
       (SELECT Id FROM tesTramites WHERE IdTesista1=tblTesistas.Id  OR IdTesista2=tblTesistas.Id)

-- vistas

;;FUNCIONES DE CLASIFICACION DE VOCALES y SECUENCIAS VOCALICAS
(in-package :nlpes)

(load "modelo")

(defun in-aou? (caracter)
  (when caracter 
    (member (char-upcase caracter) '(#\A #\O #\U #\Á #\Ó #\Ú #\Ü))))

(defun in-ao? (caracter)
  (when caracter 
    (member (char-upcase caracter) '(#\A #\O #\Á #\Ó))))

(defun in-ei? (caracter)
  (when caracter 
    (member (char-upcase caracter) '(#\E #\I #\É #\Í))))

(defmethod vocal-abierta-acentuada? ((caracter symbol)) 
  (find caracter '(Á É Ó)))

(defmethod vocal-abierta-acentuada? ((caracter fonema)) 
  (find (fonema-valor caracter) '("Á" "É" "Ó" "á" "é" "ó")  
	:test #'equalp))

(defmethod vocal-abierta-acentuada? ((caracter character))
  (find (char-upcase caracter) '(#\Á #\É #\Ó)))


(defmethod vocal-abierta-no-acentuada? ((caracter symbol))
  (find caracter '(a e o A E O)))

(defmethod vocal-abierta-no-acentuada? ((caracter fonema))
  (find (fonema-valor caracter) '("a" "e" "o" "A" "E" "O") 
	:test #'equalp))

(defmethod vocal-abierta-no-acentuada? ((caracter character))
  (find (char-upcase caracter) '(#\A #\E #\O)))

(defmethod vocal-cerrada-acentuada? ((caracter symbol))
  (find caracter '(Ú Í ú í)))

(defmethod vocal-cerrada-acentuada? ((caracter fonema))
  (find (fonema-valor caracter) '("Ú" "Í" "í" "ú") 
	:test #'equalp))

(defmethod vocal-cerrada-acentuada? ((caracter character))
  (find (char-upcase caracter) '(#\Í #\Ú)))

(defmethod vocal-cerrada-no-acentuada? ((caracter symbol))
  (member caracter '(i u I U Ü ü)))

(defmethod vocal-cerrada-no-acentuada? ((caracter character))
  (find (char-upcase caracter) '(#\I #\U #\Ü)))

(defmethod vocal-cerrada-no-acentuada? ((caracter fonema))
  (or (equal (fonema-simbolo caracter) "I") 
      (find (fonema-valor caracter) 
	    '("i" "u" "I" "U" "Ü" "Ï") 
	    :test #'equalp)))

(defmethod vocal-abierta? (caracter)
  (or (vocal-abierta-no-acentuada? caracter)
      (vocal-abierta-acentuada? caracter)))

(defmethod vocal-cerrada? (caracter)
  (or (vocal-cerrada-no-acentuada? caracter)
      (vocal-cerrada-acentuada? caracter)))

(defmethod vocal-acentuada? (caracter)
  (or (vocal-abierta-acentuada? caracter)
      (vocal-cerrada-acentuada? caracter)))	

(defmethod vocal? (caracter)
  (or (vocal-abierta? caracter) 
      (vocal-cerrada? caracter)))

;;Definir lo de las acentaciones y el hiato que interfiere
;;ver casos como chiita y duunviro
(defun diptongo (caracter1 caracter2)
  (or (and (vocal-abierta? caracter1) 
	   (vocal-cerrada-no-acentuada? caracter2))
      (and (vocal-abierta? caracter2)
	   (vocal-cerrada-no-acentuada? caracter1))
      (and (vocal-cerrada-no-acentuada? caracter1)
	   (vocal-cerrada-no-acentuada? caracter2)
	   (not (equal (fonema-valor caracter1) 
		       (fonema-valor caracter2))))))				

(defun hiato (caracter1 caracter2)
  (or (and (vocal-abierta? caracter1) 
	   (vocal-abierta? caracter2))
      (and (vocal-cerrada-acentuada? caracter1)
	   (vocal-abierta? caracter2))
      (and (vocal-abierta? caracter1)
	   (vocal-cerrada-acentuada? caracter2))))

(defun triptongo (caracter1 caracter2 caracter3)
  (and (vocal-cerrada-no-acentuada? caracter1)
       (vocal-abierta? caracter2)
       (vocal-cerrada-no-acentuada? caracter3)))	


(in-package :nlpes)

(load "fonemas")

(defun separador (bandera)
  (and bandera 
       (list (make-fonema :valor "-"))))

(defun es-h (fon)
  (and fon (equal (fonema-valor fon) "H")))

;;La funcion principal
(defun silabas (palabra)
  (ataque (fonemas palabra) t))

;;Para convertir las silabas, mostradas en formato de fonemas
;;a un formato + simple.
(defun silabas->cadena (palabra)
  (reduce (lambda (x y) (concatenate 'string x (fonema-valor y))) 
	  (cdr (silabas palabra))
	  :initial-value ""))


(defun silabas->pprint (palabra)
  (mapcar (lambda (c) 
	    (reduce (lambda (x y) (concatenate 'string x (fonema-valor y))) c 
		    :initial-value "")) 
	  (silabas->lista palabra)))			

;;Para convertir al siguiente formato 
;;'(a b r a z o) ->'((a) (b r a) (z o)) que es en cierta forma útil
(defun silabas->lista (palabra)				;;pretty dirty ...
  (let ((acc nil) (result nil))
    (loop for i in (append (cdr (silabas palabra)) 
			   (list (make-fonema :valor "-"))) 
       do (if (equal (fonema-valor i) "-")
	      (progn (setf result (append result (list acc)))
		     (setf acc nil))
	      (setf acc (append acc (list i)))))
    result))

(defun ataque (lista &optional inicio)
  (append (separador inicio)
	  (if lista
	      (cond ((vocal? (car lista)) 
		     (nucleo lista))
		    (t (cons (car lista) 
			     (ataque (cdr lista))))))))

;;Esta funcion analiza el nucleo de la silaba, es decir
;;la parte donde se encuentra(n) la(s) vocal(es).
(defun nucleo (lista)
  (cond ((vocal? (second lista))
	 (cond ((vocal? (third lista))
		(tres-vocales lista))
	       ((es-h (third lista))
		(if (vocal? (fourth lista))
		    (tres-vocales lista '2-H)
		    (dos-vocales lista)))
	       (t (dos-vocales lista)))) 
	((es-h (second lista))
	 (if (vocal? (third lista))
	     (if (vocal? (fourth lista))
		 (tres-vocales lista '1-H)
		 (dos-vocales lista 'H))
	     (vocal-simple lista)))
	(t (vocal-simple lista))))

(defun vocal-simple (lista)
  (cons (car lista) (coda (cdr lista))))

;;FUNCION PARA ANALIZAR SECUENCIAS CON 2 VOCALES consECUTIVAS
;;CON O SIN H-INTERMEDIA
;;Nota:Presuponemos que la h no influye en los diptongos ni los hiatos
;;(ni rompe diptongos ni crea hiatos).
;;Nota:Cabe destacar que cuando hay hiato y h-intermedia se puede dejar 
;;el codigo sin cambio pues el algoritmo general de separacion vocalica
;;asume ya que la H comienza la siguiente silaba y separa correctamente.
(defun dos-vocales (lista &optional hval)
  (let ((a (first lista)) (b (second lista)) (c (third lista)))
    (if (diptongo a (if hval c b))
	(append-all a 
		    b 
		    (if hval c) 
		    (coda (nthcdr (+ 2 (if hval 1 0)) lista)))
	(cons a (ataque (cdr lista) t)))))

;;Este codigo hay que probarlo!!
;;Ver lo de la posición de la H(criterios)
(defun tres-vocales (lista &optional hpos)
  (let ((a (first lista)) 
	(b (second lista)) 
	(c (third lista)) 
	(d (fourth lista)))
    (cond ((triptongo a 
		      (if (eq hpos '1-H) c  b)
		      (if hpos d c))
	   (append-all a b c (if hpos d) 
		       (nthcdr (+ 3 (if hpos 1 0)) lista)))
	  ((vocal-cerrada-acentuada? a)
	   (cons a (ataque (cdr lista) t)))
	  (t (append-all a b (ataque (cddr lista) t))))))

;;Esta funcion analiza la parte final de una silaba
;;y conecta el final con el inicio de la silaba siguiente.
;;La coda (si existe) esta formada por la(s) consonantes al final de la 
;;silaba. 		
(defun coda (lista)
  (if (cdr lista)
      (cond ((vocal? (second lista)) 
	     (ataque lista t))
	    ((vocal? (third lista))
	     (cons (car lista) (ataque (cdr lista) t)))
	    (t (cons (car lista) 
		     (cons (second lista) (ataque (cddr lista) t)))))			
      lista))

(in-package :nlpes)
;; DETECCION DE GRUPOS FONICOS EN CASTELLANO
 
;(load "vocales") 
;(load "utiles")

;;Esta funcion determinar los caracteres (mas especificamente las 
;;consonantes) que tienen su sonido inherente unicamente a su simbolo.
(defun sonido-literal? (caracter)
	(member caracter
		'(#\j #\m #\n #\s #\w #\x #\U00D1 #\J #\M #\N #\S #\W #\X #\U00F1)))

;;Algoritmo para identificar los grupos fonicos 
;;de una palabra.La palabra se representa como una lista de simbolos.
(defun fonemas (palabra) 
  (loop for i below (length palabra)
     if (null (pass))
     collect (gf-procesar-caracter palabra i)))

;;Para procesar cada caracter de la 'palabra' pasada como argumento
;;a 'fonemas'
(defun gf-procesar-caracter (palabra i)
  (let ((val (aref palabra i)))
    (if (or (vocal? val) (sonido-literal? val))
	(make-fonema 
	 :valor (make-string 1 :initial-element (char-upcase val)))
	(case (char-upcase val) 
	  ((#\B) (gf-caso-b palabra i))
	  ((#\C) (gf-caso-c palabra i))
	  ((#\D) (gf-caso-d palabra i))
	  ((#\F) (gf-caso-f palabra i))
	  ((#\G) (gf-caso-g palabra i))
	  ((#\H) (make-fonema :valor "H"))
	  ((#\K) (gf-caso-k palabra i))
	  ((#\P) (gf-caso-p palabra i))
	  ((#\Q) (gf-caso-q palabra i))
	  ((#\R) (gf-caso-r palabra i))
	  ((#\T) (gf-caso-t palabra i))
	  ((#\L) (gf-caso-l palabra i))
	  ((#\V) (make-fonema :valor "V" 
			      :simbolo "B"))
	  ((#\Y) (gf-caso-y palabra i))
	  ((#\Z) (make-fonema :valor "Z" 
			      :simbolo "S"))))))

;;funciones para las alternativas dentro del case
;;(me quede aqui tratando de crear un predicado que permita comparar multiples ;;valores)  
(defun gf-caso-b (palabra i)
	(let ((tmp (next-char palabra i)))
		(cond ((and tmp (equal (char-upcase tmp) #\R)) 
				(mark-to-pass) (make-fonema :valor "BR"))
			  ((and tmp (equal (char-upcase tmp) #\L)) 
				(mark-to-pass) (make-fonema :valor "BL"))
			  (t (make-fonema :valor "B")))))

(defun gf-caso-c (palabra i)
	(let ((tmp (next-char palabra i)))
	  (cond ((in-ei? tmp) 
		 (make-fonema :valor "C" :simbolo "S"))
		((in-aou? tmp)	
		 (make-fonema :valor "C" :simbolo "K"))
			  ((and tmp (equal (char-upcase tmp) #\H)) 
			   (mark-to-pass) (make-fonema :valor "CH"))
			  ((and tmp (equal (char-upcase tmp) #\R)) 
			   (mark-to-pass) (make-fonema :valor "CR"))
			  ((and tmp (equal (char-upcase tmp) #\L)) 
			   (mark-to-pass) (make-fonema :valor "CL"))
			  (t (make-fonema :valor "C")))))
		  
(defun gf-caso-d (palabra i)
  (let ((tmp (next-char palabra i)))
	  (cond ((and tmp (equal (char-upcase tmp) #\R)) 
		 (mark-to-pass) (make-fonema :valor "DR"))
		(t (make-fonema :valor "D")))))

(defun gf-caso-f (palabra i)
	(let ((tmp (next-char palabra i)))
	  (cond ((and tmp (equal (char-upcase tmp) #\R)) 
		 (mark-to-pass) (make-fonema :valor "FR"))
		((and tmp (equal (char-upcase tmp) #\L)) 
		 (mark-to-pass) (make-fonema :valor "FL"))
		(t (make-fonema :valor "F")))))

(defun gf-caso-l (palabra i)
  (let ((tmp (next-char palabra i)))
	(cond ((null tmp) (make-fonema :valor "L"))
		  ((equal (char-upcase tmp) #\L) 
			(mark-to-pass) 
			(make-fonema :valor "LL"
					:simbolo "Y"))
		  (t (make-fonema :valor "L")))))

(defun gf-caso-g (palabra i) 
	(let ((tmp (next-char palabra i)))
	  (cond ((in-ao? tmp) 
		 (make-fonema :valor "G"))
		((in-ei? tmp) (make-fonema :valor "G" 
					   :simbolo "J"))  
		((equal (char-upcase tmp) #\L) 
		 (mark-to-pass) (make-fonema :valor "GL"))
		((equal (char-upcase tmp) #\R) 
		 (mark-to-pass) (make-fonema :valor "GR"))
		((or (eq tmp #\U00FC) (eq tmp #\U00DC))	;u con dierisis
		 (mark-to-pass) (make-fonema :valor "GÜ" 
					     :simbolo "W"))
		((and tmp
		      (and (equal (char-upcase tmp) #\U) 
			   (in-ei? (aref palabra (+ i 2))))) 
		 (mark-to-pass) (make-fonema :valor "GU"
					     :simbolo "G"))
		((and tmp 
		      (and (equal (char-upcase tmp) #\U) 
			   (in-ao? (aref palabra (+ i 2)))))
		 (mark-to-pass) (make-fonema :valor "GU" 
					     :simbolo "G"))
		(t (make-fonema :valor "G")))))

(defun gf-caso-k (palabra i)
  (let ((tmp (next-char palabra i)))
    (cond ((and tmp (equal (char-upcase tmp) #\R)) 
	   (mark-to-pass) (make-fonema :valor "KR"))
	  (t (make-fonema :valor "K")))))
		   
(defun gf-caso-p (palabra i)
  (let ((tmp (next-char palabra i)))	
    (cond ((and tmp (equal (char-upcase tmp) #\R)) 
	       (mark-to-pass) (make-fonema :valor "PR"))
	  ((and tmp (equal (char-upcase tmp) #\L)) 
	   (mark-to-pass) (make-fonema :valor "PL"))
	  (t (make-fonema :valor "P")))))

(defun gf-caso-q (palabra i)
	(mark-to-pass) 
	(make-fonema :valor "QU"
		     :simbolo 'k))

(defun gf-caso-r (palabra i)
  (let ((tmp (next-char palabra i)))
    (cond ((and tmp	(equal (char-upcase tmp) #\R)) 
	       (mark-to-pass) (make-fonema :valor "RR"))
	      (t (make-fonema :valor "R")))))

(defun gf-caso-t (palabra i)
  (let ((tmp (next-char palabra i))) 	
    (cond ((and tmp (equal (char-upcase tmp) #\R))
	       (mark-to-pass) (make-fonema :valor "TR"))
	      ((and tmp (equal (char-upcase tmp) #\L)) 
		   (mark-to-pass) (make-fonema :valor "TL"))
		  (t (make-fonema :valor "T")))))

	  
(defun encliticos (palabra i)
  (let ((size (length palabra)))
	  (cond ((oddp (- size (1+ i))) nil)
		      (t (check (subseq palabra (1+ i) size))))))
		  
(defun gf-caso-y (palabra i)
  (cond ((zerop i) 
		  (if (next-char palabra i)
			(make-fonema :valor "Y" :simbolo 'y)
			(make-fonema :valor "Y" :simbolo "I")))
	     ((= i (1- (length palabra))) (make-fonema :valor "Y"
							:simbolo "I")) 
		 ((encliticos palabra i) (make-fonema  :valor "Y"
							:simbolo "I"))
		  (t (make-fonema :valor "Y" :simbolo 'y))))

(defun check (cadena)
  (let ((tmp '("me" "te" "se" "lo" "la" "le")))
	  (notany #'null
		  (loop for j below (length cadena) 
		     by 2 collect 
		       (some 
			(lambda (c) (equal (subseq cadena j (+ j 2)) c)) 
			tmp)))))

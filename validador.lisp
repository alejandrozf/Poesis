(defpackage poesia.validador
  (:use :cl :nlpes))

(in-package :poesia.validador)

(defstruct poema ()
  versos rima nsilabas estructura)

(defmethod poema-consistente? ((p poema))
  (values))
  
  
			       
    



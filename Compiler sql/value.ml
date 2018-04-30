(* Module pour la représentation et la manipulation des valeurs atomiques
 *
 * Ce module respecte la signature Relation.DATA et peut donc être utilisé
 * en argument de Relation.Make
 *)


(* Définition des types relatant des domaines et des valeurs atomiques manipulables *)

type domain =
  | DInt
  | DFloat
  | DVChar

type value =
  | VInt   of int
  | VFloat of float
  | VVChar of string

(* Fonctions de conversion entre chaînes de caractères et valeurs/domaines (utilisées dans l'import/export des CSV) *)

let domain_of_string s =
  match s with
  | "INT" -> DInt
  | "FLOAT" -> DFloat
  | "VARCHAR" -> DVChar
  | _ -> failwith (Printf.sprintf "Value: domain_of_string: unknown domain: '%s'" s)

let string_of_domain d =
  match d with
  | DInt -> "INT"
  | DFloat -> "FLOAT"
  | DVChar -> "VARCHAR"

let value_of_string d =
  match d with
  | DInt -> (fun s -> VInt (int_of_string s))
  | DFloat -> (fun s -> VFloat (float_of_string s))
  | DVChar -> (fun s -> VVChar s)

let string_of_value v =
  match v with
  | VInt i -> string_of_int i
  | VFloat f -> string_of_float f
  | VVChar s -> s

(* Fonctions de conversion et de vérification d'appartenance d'une valeur à un domaine *)

let domain_of_value v =
  match v with
  | VInt _ -> DInt
  | VFloat _ -> DFloat
  | VVChar _ -> DVChar

let domain d v =
  match d, v with
  | DInt, VInt _
  | DFloat, VFloat _
  | DVChar, VVChar _ -> true
  | _ -> false

let to_domain d =
  match d with
  | DInt -> (function
    | VInt i -> VInt i
    | VFloat f -> VInt (int_of_float f)
    | VVChar s -> try VInt (int_of_string s) with Failure _ -> VInt 0
  )
  | DFloat -> (function
    | VInt i -> VFloat (float_of_int i)
    | VFloat f -> VFloat f
    | VVChar s -> try VFloat (float_of_string s) with Failure _ -> VFloat 0.
  )
  | DVChar -> (function
    | VInt i -> VVChar (string_of_int i)
    | VFloat f -> VVChar (string_of_float f)
    | VVChar s -> VVChar s
  )

(* Fonction spécifique de manipulation des valeurs (comparaison, addition, concaténation, etc.) *)

let combine_arith v1 v2 op_i op_f op_s=
  match (v1, v2) with
  | VInt i1, VInt i2 -> VInt (op_i i1  i2)
  | VInt i1, VFloat f1 -> VFloat (op_f (float_of_int i1) f1)
  | VFloat f1, VInt i1  -> VFloat (op_f f1 (float_of_int i1))
  | VFloat f1, VFloat f2 -> VFloat (op_f f1 f2)
  | _ -> failwith (Printf.sprintf "Value: add: type error: '%s %s %s'" (string_of_value v1) op_s (string_of_value v2))


let add v1 v2 = combine_arith v1 v2 (+) (+.) "+"
let sub v1 v2 = combine_arith v1 v2 (-) (-.) "-"
let mul v1 v2 = combine_arith v1 v2 ( * ) ( *. ) "*"
let div v1 v2 = combine_arith v1 v2 ( / ) ( /. ) "/"

let neg v1 = 
  match v1 with 
  |VInt i -> VInt ( (-1) * i )
  |VFloat f -> VFloat ( (-1.) *. f)
  | _ -> failwith (Printf.sprintf "Value: neg: type error: '%s'" (string_of_value v1))

let concat v1 v2 =
  match (v1, v2) with
  | VVChar s1, VVChar s2 -> VVChar (s1 ^ s2)
  | VFloat f1 , VFloat f2 -> VVChar ((string_of_float f1)^(string_of_float f2))
  | VInt i1 , VInt i2 -> VVChar  ((string_of_int i1)^(string_of_int i2))
  | VInt i1, VFloat f1 ->  VVChar ((string_of_int i1)^(string_of_float f1))
  | VFloat f1, VInt i1  ->  VVChar ((string_of_float f1)^(string_of_int i1))
  | _ -> failwith (Printf.sprintf "Value: concat: type error: '%s || %s'" (string_of_value v1) (string_of_value v2))

let lower v1 = 
  match v1 with
  | VVChar s -> VVChar (String.lowercase_ascii s)
  | VInt i ->  VVChar (string_of_int i)
  | VFloat f -> VVChar (string_of_float f)

let upper v1 = 
  match v1 with
  | VVChar s -> VVChar (String.uppercase_ascii s)
  | VInt i ->  VVChar (string_of_int i)
  | VFloat f -> VVChar (string_of_float f)


let substring v1 v2 v3 = 
  match (v1,v2,v3) with
  | (VVChar s, VInt i1, VInt i2) -> VVChar (String.sub s i1 i2 ) 
  |_ -> failwith (Printf.sprintf "Value: substring: type error: 'SUBSTRING (%s FROM  %s FOR %s )'" (string_of_value v1) (string_of_value v2) (string_of_value v3))



let combine_cmp v1 v2 cmp_i cmp_f cmp_s = match v1,v2 with
  |VInt i1, VInt i2 ->   cmp_i i1  i2
  |VFloat f1, VFloat f2 ->  cmp_f f1 f2 
  |VFloat f1, VInt i1 ->  cmp_f f1 (float_of_int i1) 
  |VInt i1, VFloat f1 -> cmp_f (float_of_int i1) f1   
  |VVChar s1, VVChar s2 ->  cmp_s (String.compare s1 s2) 0  
  |_ -> failwith (Printf.sprintf "Comparaison : type error: not comparable types" )

let eq v1 v2 = combine_cmp v1 v2 (=) (=) (=)
let neq v1 v2 = combine_cmp v1 v2 (<>) (<>) (<>)
let lt v1 v2 = combine_cmp v1 v2 (<) (<) (<)
let gt v1 v2 = combine_cmp v1 v2 (>) (>) (>)
let le v1 v2 = combine_cmp v1 v2 (<=) (<=) (<=)
let ge v1 v2 = combine_cmp v1 v2 (>=) (>=) (>=)


let between v1 v2 v3 = match v1,v2,v3 with
  | VInt i1, VInt i2, VInt i3 -> (i2<=i1) && (i1 <= i3)
  | VInt i1, VInt i2, VFloat f1 -> (i2<=i1) && ((float_of_int i1) <= f1)
  | VInt i1 , VFloat f1 , VInt i2 -> (f1<=(float_of_int i1)) && ( i1 <= i2)
  | VFloat f1 ,VInt i1, VInt i2 -> ((float_of_int i1) <= f1) && (f1 <= (float_of_int i2))
  | VInt i1, VFloat f1, VFloat f2 -> (f1<= (float_of_int i1)) && ((float_of_int i1) <= f2)
  | VFloat f1 , VFloat f2 , VInt i1 -> (f2<=f1) && (f1 <= (float_of_int i1))
  | VFloat f1 , VInt i1, VFloat f2 -> ((float_of_int i1)<=f1) && (f1 <=f2)
  | VFloat f1, VFloat f2, VFloat f3 -> (f2<=f1) && (f1 <= f3)
  | VVChar s1 , VVChar s2 , VVChar s3 -> ((String.compare s2 s1) <= 0) && ((String.compare s1 s3) <= 0)
  |_ -> failwith (Printf.sprintf "Between : type error: not comparable types" )


let notbetween v1 v2 v3 = not (between v1 v2 v3)
    (* Module pour la représentation et la manipulation des données élémentaires
     *
     * Ce module respecte la signature Relation.DATA et peut donc être utilisé
     * en argument de Relation.Make
     *)
     
    (** Définition des types représentant les trois domaines possibles et les valeurs correspondantes **)
     
    type domain =
      | DInt
      | DFloat
      | DVChar
     
    type value =
      | VInt   of int
      | VFloat of float
      | VVChar of string
     
    (** Fonctions de conversion entre chaînes de caractères et valeurs/domaines (notamment utilisées dans l'import/export des CSV) **)
     
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
     
    (** Opérations supplémentaires **)
     
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
      | DInt -> (fun v -> match v with
        | VInt i -> VInt i
        | VFloat f -> VInt (int_of_float f)
        | VVChar s -> try VInt (int_of_string s) with Failure _ -> VInt 0
      )
      | DFloat -> (fun v -> match v with
        | VInt i -> VFloat (float_of_int i)
        | VFloat f -> VFloat f
        | VVChar s -> try VFloat (float_of_string s) with Failure _ -> VFloat 0.
      )
      | DVChar -> (fun v -> match v with
        | VInt i -> VVChar (string_of_int i)
        | VFloat f -> VVChar (string_of_float f)
        | VVChar s -> VVChar s
      )
     
    (* Fonctions spécifiques de manipulation des valeurs (comparaison, addition, concaténation, etc.) *)
     
    let add v1 v2 =
      match (v1, v2) with
      | VInt i1, VInt i2 -> VInt (i1 + i2)
      | VFloat f1, VFloat f2 -> VFloat (f1 +. f2)
      | _ -> failwith (Printf.sprintf "Value: add: type error: '%s + %s'" (string_of_value v1) (string_of_value v2))
     
    let concat v1 v2 =
      match (v1, v2) with
      | VVChar s1, VVChar s2 -> VVChar (s1 ^ s2)
      | _ -> failwith (Printf.sprintf "Value: concat: type error: '%s || %s'" (string_of_value v1) (string_of_value v2))


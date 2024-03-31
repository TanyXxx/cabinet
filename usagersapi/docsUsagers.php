<?php

require_once '../Docs/docCentral.php'; 

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Usagers",
 *     description="Opérations sur les usagers"
 * )
 */

/**
 * @OA\Post(
 *     path="/usagers/inscription",
 *     tags={"Usagers"},
 *     summary="Inscription d'un nouvel usager",
 *     description="Crée un nouvel usager à partir des données fournies.",
 *     operationId="inscriptionUsager",
 *     @OA\RequestBody(
 *         description="Informations de l'usager pour l'inscription",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom", "prenom", "email", "motDePasse"},
 *             @OA\Property(property="nom", type="string", example="Durand"),
 *             @OA\Property(property="prenom", type="string", example="Marie"),
 *             @OA\Property(property="email", type="string", example="marie.durand@example.com"),
 *             @OA\Property(property="motDePasse", type="string", example="unMotDePasseSecurise")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usager inscrit avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Inscription réussie")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Données d'inscription invalides"
 *     )
 * )
 */


 
/**
 * @OA\Get(
 *     path="/usagers/{id}",
 *     tags={"Usagers"},
 *     summary="Obtient les détails d'un usager",
 *     description="Retourne les détails d'un usager spécifié par son ID.",
 *     operationId="getUsager",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'usager à récupérer",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Détails de l'usager",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="nom", type="string", example="Durand"),
 *             @OA\Property(property="prenom", type="string", example="Marie"),
 *             @OA\Property(property="email", type="string", example="marie.durand@example.com")
 *             // Ajoutez d'autres propriétés selon votre modèle de données
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usager non trouvé"
 *     )
 * )
 */

/**
 * @OA\Put(
 *     path="/usagers/{id}",
 *     tags={"Usagers"},
 *     summary="Mise à jour des informations d'un usager",
 *     description="Permet de mettre à jour les informations d'un usager spécifié par son ID.",
 *     operationId="updateUsager",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'usager à mettre à jour",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Informations de l'usager à mettre à jour",
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="nom", type="string", example="Leclerc"),
 *             @OA\Property(property="prenom", type="string", example="Jean"),
 *             @OA\Property(property="email", type="string", example="jean.leclerc@example.com")
 *             // Ajoutez ou modifiez les propriétés selon votre modèle de données
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usager mis à jour avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Mise à jour réussie")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Données invalides fournies"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usager non trouvé"
 *     )
 * )
 */

/**
 * @OA\Delete(
 *     path="/usagers/{id}",
 *     tags={"Usagers"},
 *     summary="Suppression d'un usager",
 *     description="Supprime un usager spécifié par son ID.",
 *     operationId="deleteUsager",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'usager à supprimer",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Usager supprimé avec succès"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usager non trouvé"
 *     )
 * )
 */



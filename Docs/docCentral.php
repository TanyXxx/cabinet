<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API Central pour le Système de Gestion Médicale",
 *         version="1.0.0",
 *         description="Ce système de gestion médicale fournit une interface pour la gestion des usagers, des consultations, des médecins, ainsi que des statistiques. Il intègre également une authentification sécurisée pour accéder à ses ressources.",
 *         @OA\Contact(
 *             email="soltan.hamadouche@gmail.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost/cabinet",
 *         description="Serveur de développement - Accès local à l'API."
 *     ),
 *     @OA\Server(
 *         url="https://soltanhamadouche.alwaysdata.net/",
 *         description="Serveur de production - Interface API publique pour accéder au système de gestion médicale."
 *     )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     in="header",
 *     description="Pour accéder aux endpoints protégés, vous devez passer un token JWT valide dans l'en-tête de la requête."
 * )
 * 
 * @OA\Tag(
 *     name="Auth",
 *     description="Opérations d'authentification"
 * )
 * @OA\Tag(
 *     name="Consultation",
 *     description="Gestion des consultations"
 * )
 * @OA\Tag(
 *     name="Medecin",
 *     description="Opérations liées aux médecins"
 * )
 * @OA\Tag(
 *     name="Statistiques",
 *     description="Endpoints pour les statistiques"
 * )
 * @OA\Tag(
 *     name="Usagers",
 *     description="Gestion des usagers"
 * )
 */

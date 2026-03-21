<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Portfolio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $portfolio = Portfolio::first();
        $toEmail = $portfolio?->email ?? config('mail.from.address');

        if (!$toEmail) {
            return response()->json([
                'success' => false,
                'error'   => 'Adresse email de destination non configurée',
            ], 500);
        }

        try {
            Mail::to($toEmail)->send(new ContactMail(
                senderName:  $validator->validated()['name'],
                senderEmail: $validator->validated()['email'],
                messageBody: $validator->validated()['message'],
            ));
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Erreur lors de l\'envoi du mail: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé avec succès',
        ]);
    }
}

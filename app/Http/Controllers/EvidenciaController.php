<?php

namespace App\Http\Controllers;

use App\Avance;
use App\Bitacora;
use App\Evidencia;
use App\Http\Requests\SaveEvidenciaRequest;
use App\Mail\NotificationToMail;
use App\Notifications\NotificateAvance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EvidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Avance $avance
     * @return \Illuminate\Http\Response
     */
    public function index(Avance $avance)
    {
        return view('evidenciaOperations.index', [
            'total_evid' => Evidencia::all(),
            'avance' => $avance
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param SaveEvidenciaRequest $request
     * @return \Illuminate\Http\Response
     */
    // SUBIR UNA EVIDENCIA PARA ALGUN AVANCE
    public function create()
    {
        return view('evidenciaOperations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveEvidenciaRequest $request)
    {
        Evidencia::create([
            'ubi_archivo' => request('archivo')->store('public'),
            'name_alumno' => request('name_user'),
            'name_evid' => \request('name_evid'),
            'avance_id' => \request('avance_id')
        ], $request->validated());

        // bitacora en la ue se hace el avance
        $bitacora = Avance::find(request('avance_id'))->bitacora;

        // usuarios para recibir notificaciones sobre la bitacora
        $users = $bitacora->users;

        // enviar notificacion
        foreach ($users as $us)
            if ($us->rol == 'Profesor' || $us->rol == 'Encargado Titulación') {
                $us->notify(new NotificateAvance('Evidencia', $bitacora->titulo));
                //Mail::to($us->email)->queue(new NotificationToMail);
            }

        return view('home');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

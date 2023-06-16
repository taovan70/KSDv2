<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateLanguageRequest;
use App\Models\User;
use Prologue\Alerts\Facades\Alert;

class LangController extends Controller
{
    public function setLanguage(UpdateLanguageRequest $request)
    {
        $user= User::find(backpack_user()->id);
        $user->lang = $request->lang;;
        $user->save();
        Alert::success(__('table.user_fields.information_changed'))->flash();

        return redirect()->back();
    }
}

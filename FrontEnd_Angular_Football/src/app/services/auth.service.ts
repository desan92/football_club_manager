import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { LoginForm } from '../interfaces/loginForm.interface';
import { catchError, map, Observable, of, tap } from 'rxjs';
import { Usuari } from '../models/usuari.model';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  public usuari!: Usuari;

  constructor(private http: HttpClient) { }

  get token(): string
  {
    return localStorage.getItem('x-token') || '';
  }

  guardarToken(token:string){
    localStorage.setItem('x-token', token);
  }

  validarToken(){
    //console.log(this.token);

    //aqui es fa una consulta a la db aquesta consulta retorna el token nou renovat +`les dades del usuari.
    return this.http.get(`http://localhost:8000/api/renew`, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }//pipe es un metode d'observables que permet encadenar diferents operadors rxjs
    }).pipe(
      map((resp: any) => {

        const { id, nom, cognom, email, club_id } = resp.user;
        this.usuari = new Usuari(id, nom, cognom, email, club_id);

        this.guardarToken(resp.token);

        return true;
      }),
      catchError(error => of(false))
    )

  }

  login(params: LoginForm)
  {
    return this.http.post('http://localhost:8000/api/login', params).pipe(
      tap((resp: any) => {
        //console.log(resp);
        this.guardarToken(resp.token);
      })
    );
  }

  logout(){

    const data = {};
    const headers = {
      //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
      //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
      "Content-Type":  "application/json",
      Accept: "application/json",
      Authorization: 'Bearer ' + this.token
    }

    localStorage.removeItem('x-token');

    return this.http.post('http://localhost:8000/api/logout', data, {headers});
  }

}


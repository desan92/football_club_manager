import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, CanLoad, Route, Router, RouterStateSnapshot, UrlSegment, UrlTree } from '@angular/router';
import { Observable, tap } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

  constructor(private authService: AuthService,
    private router: Router) {}

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
      //this.router.navigateByUrl('/home');
      //return true;
      return this.authService.validarToken().pipe(
        //es comprova que l'usuari existeix sino es treu del dashbord i s'envia al login
        tap(isAutenticate => {
          if(!isAutenticate)
          {
            this.router.navigateByUrl('/login');
          }
        })
      );
  }
  canLoad(
    route: Route,
    segments: UrlSegment[]): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
      //this.router.navigateByUrl('/home');
      //return true;
      return this.authService.validarToken().pipe(
        //es comprova que l'usuari existeix sino es treu del dashbord i s'envia al login
        tap(isAutenticate => {
          if(!isAutenticate)
          {
            this.router.navigateByUrl('/login');
          }
        })
      );
  }
}

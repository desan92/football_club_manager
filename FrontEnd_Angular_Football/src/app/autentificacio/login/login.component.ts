import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import Swal from 'sweetalert2';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {

  msgErrors: string = '';

  public loginForm: FormGroup = this.fb.group({
    email: ['', [Validators.required, Validators.email]],
    password: ['', [Validators.required, Validators.minLength(6)]]
  });

  constructor(private fb: FormBuilder,
              private authService: AuthService,
              private router: Router){}

  campNoValid(camp: string): boolean{
    if(this.loginForm.get(camp)?.invalid && this.loginForm.controls[camp]?.touched)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  onSubmit() {
    this.authService.login(this.loginForm.value).subscribe( resp  => {
      console.log(resp);
      this.router.navigateByUrl('/home');
    }, (err) => {
      Swal.fire('Error', err.error.message, 'error');
    });
    
  }
}

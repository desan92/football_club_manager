import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TransferenciesComponent } from './transferencies.component';

describe('TransferenciesComponent', () => {
  let component: TransferenciesComponent;
  let fixture: ComponentFixture<TransferenciesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TransferenciesComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TransferenciesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

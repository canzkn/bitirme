import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ShowQuestionPageRoutingModule } from './show-question-routing.module';

import { ShowQuestionPage } from './show-question.page';

import { ComponentsModule } from '../../../components/components.module';

import { HighlightModule } from 'ngx-highlightjs';

import { QuillModule } from 'ngx-quill'

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ShowQuestionPageRoutingModule,
    ComponentsModule,
    HighlightModule,
    QuillModule.forRoot({
      modules: {
        syntax: true
      }
    })
  ],
  declarations: [ShowQuestionPage]
})
export class ShowQuestionPageModule {}

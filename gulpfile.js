import gulp from "gulp";
import * as dartSass from "sass";
import gulpSass from 'gulp-sass'; // para conectar sass con gulp
import plumber from "gulp-plumber"; // para evitar que se detenga los procesos ante errores
import sourcemaps from "gulp-sourcemaps"; // para incicar donde se encuentra el elemento en el src
import webp from "gulp-webp";
import ts from "gulp-typescript";
import autoprefixer from "autoprefixer"; // para hacer el css compatible con otros navegadores
import cssnano from "cssnano"; // para hacer mas ligero el css
import postcss from "gulp-postcss"; // para mejorar el codido css
import terser from "gulp-terser-js"; // para mejorar el codigo de javascript
import imagemin from "gulp-imagemin";

const { src, dest, watch, parallel } = gulp;
const sass = gulpSass(dartSass);
const tsProject = ts.createProject("tsconfig.json");

// un pipe es una accion que se realiza despues de otra

const pathsBuild = {
    sassPath: "public/build/css",
    imgPath: "public/build/img",
    typescriptPath: "public/build/js"
}

export function compilarSASS(done) {
    // Identificar el archivo SASS
    src("src/scss/app.scss")
        .pipe(sourcemaps.init())
        .pipe(plumber())
        // Compilar a css
        .pipe(sass())
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(sourcemaps.write("."))
        // Almanenarlo
        .pipe(dest(pathsBuild.sassPath))
    done(); // done -> avisa a gulp que la tarea ha finalizado
}

export function versionWebp(done) {

    const opciones = {
        quality: 90
    }

    src("src/img/**/*.{png,jpg}")
        .pipe(webp(opciones))
        .pipe(dest(pathsBuild.imgPath))
    done()
}

export function compilarTypeScript(done) {
    src("src/ts/*.ts")
        .pipe(sourcemaps.init())
        .pipe(plumber())
        .pipe(tsProject())
        .pipe(terser())
        .pipe(sourcemaps.write("."))
        .pipe(dest(pathsBuild.typescriptPath))
    done()
}

export function imagenesLigeras(done) {
    src("src/img/**/*")
        .pipe(imagemin({ optimizationLevel: 3 }))
        .pipe(dest(pathsBuild.imgPath))
    done();
}

export function dev(done) {
    watch("src/scss/**/*.scss", compilarSASS);
    watch("src/ts/*.ts", compilarTypeScript);
    done();
}

export const build = parallel(imagenesLigeras, versionWebp, compilarSASS, compilarTypeScript);

export default parallel(imagenesLigeras, versionWebp, compilarSASS, compilarTypeScript, dev);